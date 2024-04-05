<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\OrderRequest;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\HtmlString;

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpParser\Node\Expr\Cast\String_;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $closedOrders = Order::query()->where('status', 'closed')->count();
        $paidOrders = Order::query()->where('status', 'paid')->count();
        $pendingOrders = Order::query()->where('status', 'pending')->count();
        $canceledOrders = Order::query()->where('status', 'canceled')->count();

        $filterByYear = $request->year ?? '';
        $filterByStatus = $request->status ?? '';
        $filterByDate = $request->date ?? '';
        $filterByCustomer = $request->customer ?? '';

        //Query para o gráfico de encomendas fechadas por mês
        $closedOrdersPerMonthQuery = Order::selectRaw('COUNT(*) as count, MONTH(date) as month')
            ->where('status', 'closed')
            ->groupBy('month')
            ->orderBy('month');

        //Query para a tabela de encomendas
        $orderQuery = Order::query();

        //Filtrar por cliente (tabela)
        if ($filterByCustomer != '') {
            $customerIds = User::where('name', 'like', "%$filterByCustomer%")->pluck('id');
            $orderQuery->whereIntegerInRaw('customer_id', $customerIds);
        }

        //Filtrar por status (tabela)
        if ($filterByStatus != '') {
            $orderQuery->where('status', 'LIKE', $filterByStatus);
        }

        //Filtrar por data (tabela)
        if ($filterByDate != '') {
            $orderQuery->where('date', 'LIKE', $filterByDate);
        }

        //Filtrar por ano (query para o gráfico)
        if ($filterByYear != '') {
            $closedOrdersPerMonthQuery->whereYear('date', $filterByYear);
        }

        //Paginação (tabela)
        if ($request->user()->user_type != 'A') {
            $orderQuery->where('status', '!=', 'closed');
            $orderQuery->where('status', '!=', 'canceled');
        }

        $orders = $orderQuery->paginate(10);

        //Array com o número de encomendas fechadas por mês
        $closedOrdersPerMonth = $closedOrdersPerMonthQuery->pluck('count', 'month')->toArray();
        $closedOrdersPerMonth = array_replace(array_fill(1, 12, 0), $closedOrdersPerMonth);
        $closedOrdersPerMonth = array_values($closedOrdersPerMonth);

        //converter para json (usado no gráfico (js))
        $jsonClosedOrdersPerMonth = json_encode($closedOrdersPerMonth);

        $userType = $request->user()->user_type;

        return view('orders.index', compact('orders', 'closedOrders', 'paidOrders', 'pendingOrders', 'canceledOrders', 'filterByStatus', 'filterByDate', 'filterByCustomer', 'filterByYear', 'jsonClosedOrdersPerMonth', 'userType'));
    }

    public function show(Order $order, Request $request)
    {
        $userType = $request->user()->user_type;
        if (($order->status == 'closed' || $order->status == 'canceled') && $userType != 'A') {
            return redirect()->route('orders.index')
                ->with('alert-msg', "Não tem permissões para aceder a esta encomenda!")
                ->with('alert-type', 'danger');
        }

        return view('orders.show', compact('order', 'userType'));
    }

    public function edit(Order $order, Request $request): View
    {
        $userType = $request->user()->user_type;
        return view('orders.edit', compact('order', 'userType'));
    }

    public function minhasEncomendas(Request $request): View
    {
        $orders = $request->user()->customer->orders()->orderBy('date', 'desc')->get();
        return view('orders.minhas')->with('orders', $orders);
    }

    public function minhaEncomenda(Order $order): View
    {
        return view('orders.minha')->with('order', $order);
    }

    public function getFatura(Request $request)
    {
        $receipt_url = $request->receipt_url;
        // Verifica se existe o nome do ficheiro na base de dados
        if ($receipt_url == null) {
            abort(404);
        }

        $path = storage_path('app/pdf_receipts/' . $receipt_url);
        // Verifica se o ficheiro existe na pasta storage/app/pdf_receipts
        if (!File::exists($path)) {
            abort(404);
        }

        cache()->forget($path);
        $response = response()->file($path);
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        return $response;
    }

    public function update(OrderRequest $request, Order $order): RedirectResponse
    {
        if ($request->status == "canceled") {
            if ($request->user()->user_type != 'A') {
                return redirect()->route('orders.index')->with('alert-msg', 'Não tem permissões para cancelar encomendas!')
                    ->with('alert-type', 'danger');;
            }
        }

        if ($request->status == 'closed' && $order->status != 'closed') {
            //Criar pdf da fatura

            $pdf = new Dompdf();
            $pdfOptions = new Options();
            $pdfOptions->set('isRemoteEnabled', true);
            $pdf->setOptions($pdfOptions);

            $stylePath = storage_path('app/stylefatura.css');
            $styleContent = file_get_contents($stylePath);

            $pdfContent = '<html lang="en"> <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Document</title>
                <style>' . $styleContent . ' </style>
            </head>
            <body> <table class="body-wrap">
                    <tbody><tr><td></td>
                        <td class="container" width="600">
                            <div class="content">
                                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <img class="img-logo" src="' . asset("img/logo.png") . '" alt="logo">
                                                <span class="title-fatura">FATURA</span>
                                                <br>
                                                <br><br><br><br>
                                                <span class="info-fatura"> ImagineShirt</span>
                                                <br>
                                                <span class="info-fatura"> 2411-901 Leiria, Portugal</span>
                                            </td>
                                        </tr>
                                        <tr>
                                        <td class="content-wrap aligncenter">
                                            <table width="100%" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                    <tr>
                                                        <td class="content-block">
                                                        <br><br><br>
                                                            <h2 style="margin-left: 30% !important;">Obrigado pela sua compra!</h2>
                                                        <br><br><br>
                                                        </td>
                                                    </tr>
                                                <tr>
                                                    <td class="content-block">
                                                        <table class="invoice">
                                                            <tbody>
                                                            <tr>
                                                                <td>Cliente: ' . $order->customer->user->name . '<br>NIF: ' . $order->nif . '<br>Data da fatura: ' . date('Y-m-d') . '<br>Estado da encomenda: Fechado</td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                                        <thead>
                                                                            <tr>
                                                                                <td><b>Produto</b></td>
                                                                                <td class="center-column"><b>Quantidade</b></td>
                                                                                <td class="alignright"><b>Subtotal</b></td>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>';
            foreach ($order->orderItems as $orderItem) {
                $pdfContent .= '
                                                                                <tr>
                                                                                <td>' . $orderItem->tshirtImage->name . ' - ' . $orderItem->color->name . ' - ' . $orderItem->size . '</td>
                                                                                <td class="center-column">' . $orderItem->qty . '</td>
                                                                                <td class="alignright">' . $orderItem->sub_total . ' €</td>
                                                                                </tr>
                                                                                ';
            }

            $pdfContent .= '
                                                                            <tr class="total">
                                                                                <td class="alignright" colspan="2">Total</td>
                                                                                <td class="alignright">' . $order->total_price . '€</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody></table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="content-block">
                                                        <span style="margin-left: 27% !important;">ImagineShirt Corporation Inc. 2411-901 Leiria, Portugal</span>
                                                    </td>
                                                </tr>
                                            </tbody></table>
                                        </td>
                                    </tr>
                                </tbody></table>
                                <div class="footer">
                                </div></div>
                        </td>
                        <td></td>
                    </tr>
                </tbody></table>
            </body>
            </html>';

            $pdf->loadHtml($pdfContent);

            $pdf->render();
            $output = $pdf->output();

            // Save PDF to a file
            $pdfFilename = 'order_' . $order->id . '.pdf';
            $pdfFilePath = storage_path('app/pdf_receipts/' . $pdfFilename);
            file_put_contents($pdfFilePath, $output);

            $order->receipt_url = $pdfFilename;
            $order->save();

            $htmlMessage = "Encomenda " . $order->id . " foi alterada com sucesso! Fatura disponível para download.";
        } else {
            $htmlMessage = "Encomenda " . $order->id . " foi alterada com sucesso!";
        }

        $order->update($request->all());
        $emailPath = '';

        switch ($order->status) {
            case 'canceled':
                $emailPath = 'app/emailCanceled.html';
                break;
            case 'closed':
                $emailPath = 'app/emailClosed.html';
                break;
            default:
                break;
        }

        if (!empty($emailPath)) {
            $order->sendMail($order, $emailPath);
        }





        //Se não for um admin redireciona para a lista de encomendas
        if ($request->user()->user_type != 'A' && $request->status == 'closed') {
            return redirect()->route('orders.index')
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', 'success');
        }

        return redirect()->route('orders.show', ['order' => $order])
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', 'success');
    }
}
