@extends('template_admin.layout')

@section('main')
    <div class="container-fluid p-0">

        <a href="{{ route('orders.index') }}">
            <i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i>
        </a>

        <div class="row mt-3">
            <h1 class="h3 mb-3"><strong>Detalhes</strong> Encomenda Nº <b> {{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}
                </b> </h1>
        </div>
        <div class="row">
            <div class="col-12 col-lg-8 col-xxl-9 d-flex w-100">
                <div class="card flex-fill">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informações da encomenda</h5>
                    </div>
                    <table class="table table-hover my-0 mt-0 text-dark">
                        <tbody>
                            <tr>
                                <td>Estado da encomenda:</td>
                                <td class="d-none d-xl-table-cell">
                                    @include('orders.shared.fields', ['readonlyData' => true, 'userType' => $userType])
                                </td>
                                <td>
                                    <a href="{{ route('orders.edit', ['order' => $order]) }}" class="btn btn-primary btn-sm">
                                        Editar
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td>ID do cliente: </td>
                                <td class="d-none d-xl-table-cell">{{ $order->customer_id }}</td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Nome do cliente: </td>
                                <td class="d-none d-xl-table-cell">{{ $order->customer->user->name ?? 'null' }}</td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Data da encomenda: </td>
                                <td class="d-none d-xl-table-cell">{{ $order->date }}</td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Notas sobre a encomenda: </td>
                                <td class="d-none d-xl-table-cell">{{ $order->notes ?? '-' }}</td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>NIF:</td>
                                <td class="d-none d-xl-table-cell">{{ $order->nif }}</td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Endereço de envio: </td>
                                <td class="d-none d-xl-table-cell">{{ $order->address }}</td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Tipo de pagamento: </td>
                                <td class="d-none d-xl-table-cell">{{ $order->payment_type }}</td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Referência de pagamento: </td>
                                <td class="d-none d-xl-table-cell">{{ $order->payment_ref }}</td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Preço Total: </td>
                                <td class="d-none d-xl-table-cell">{{ $order->total_price }} €</td>
                                <td></td>
                            </tr>

                            <tr>
                                <td><i class="fa fa-file-pdf-o pdf-icon"></i> Fatura</td>
                                <td class="d-none d-xl-table-cell">
                                    @if (isset($order->receipt_url))
                                        <a target="_blank"
                                            href="{{ route('orders.fatura', ['receipt_url' => $order->receipt_url]) }}">{{ $order->receipt_url }}</a>
                                    @else
                                        Sem fatura
                                    @endif
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- <div class="col-12 col-lg-4 col-xxl-3 d-flex">
            <div class="card flex-fill w-100">
                <div class="card-header">

                    <h5 class="card-title mb-0">Monthly Sales</h5>
                </div>
                <div class="card-body d-flex w-100">
                    <div class="align-self-center chart chart-lg">
                        <canvas id="chartjs-dashboard-bar"></canvas>
                    </div>
                </div>
            </div>
        </div> --}}
        </div>


        <div class="row">
            <div class="col-12 col-lg-8 col-xxl-9 d-flex w-100">
                <div class="card flex-fill">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Items da encomenda</h5>
                    </div>
                    <table class="table table-hover my-0 mt-0 text-dark">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Nome da imagem</th>
                                <th>Código da cor</th>
                                <th>Tamanho</th>
                                <th>Quantidade</th>
                                <th>Preço Unitário</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderitems as $orderItem)
                                <tr>
                                    <td>{{ $orderItem->id }}</td>
                                    <td>{{ $orderItem->tshirtImage->name }}</td>
                                    <td>{{ $orderItem->color_code }}</td>
                                    <td>{{ $orderItem->size }}</td>
                                    <td>{{ $orderItem->qty }}</td>
                                    <td>{{ $orderItem->unit_price }} €</td>
                                    <td>{{ $orderItem->sub_total }} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- <div class="col-12 col-lg-4 col-xxl-3 d-flex">
            <div class="card flex-fill w-100">
                <div class="card-header">

                    <h5 class="card-title mb-0">Monthly Sales</h5>
                </div>
                <div class="card-body d-flex w-100">
                    <div class="align-self-center chart chart-lg">
                        <canvas id="chartjs-dashboard-bar"></canvas>
                    </div>
                </div>
            </div>
        </div> --}}
        </div>

    </div>
@endsection
