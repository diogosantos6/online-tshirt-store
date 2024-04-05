<?php

namespace App\Http\Controllers;

use App\Http\Requests\TshirtImageRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\TshirtImage;
use App\Models\Category;
use App\Models\Color;
use App\Models\Price;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TshirtImageController extends Controller
{
    public function index(Request $request): View
    {
        $clientImages = TshirtImage::query()->whereNotNull('customer_id')->count();
        $catalogueImages = TshirtImage::query()->whereNull('customer_id')->count();
        $totalImages = $clientImages + $catalogueImages;

        $categories = Category::all();

        $filterByYear = $request->year ?? '';

        $filterByCategory = $request->category ?? '';

        $filterByName = $request->name ?? '';

        $filterByDescription = $request->description ?? '';

        $year = $request->input('year', '');

        // Top 10 T-shirts mais vendidas de sempre (inclui imagens de clientes/catálogo e imagens que já não estão disponíveis para venda)
        $jsonMostSoldTshirtImagesPerMonth = DB::table('tshirt_images')
            ->join('order_items', 'tshirt_images.id', '=', 'order_items.tshirt_image_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('tshirt_images.name', DB::raw('SUM(order_items.qty) as total_quantity_sold'))
            ->where('orders.status', '=', 'closed')
            ->when($filterByYear, function ($query, $filterByYear) {
                return $query->whereYear('orders.date', $filterByYear);
            })
            ->groupBy('tshirt_images.id', 'tshirt_images.name')
            ->orderByDesc('total_quantity_sold')
            ->limit(10)
            ->get();

        // Todas as imagens da loja (clientes e catálogo)
        $tshirtImageQuery = TshirtImage::query();

        if ($filterByCategory !== '') {
            $categoryIds = Category::where('name', 'like', "%$filterByCategory%")->pluck('id');
            $tshirtImageQuery->whereIntegerInRaw('category_id', $categoryIds);
        }

        if ($filterByName !== '') {
            $tshirtImageQuery->where('name', 'like', "%$filterByName%");
        }

        if ($filterByDescription !== '') {
            $tshirtImageQuery->where('description', 'like', "%$filterByDescription%");
        }

        $tshirt_images = $tshirtImageQuery->paginate(15);

        return view('tshirt_images.index', compact('tshirt_images', 'totalImages', 'clientImages', 'catalogueImages', 'categories', 'filterByCategory', 'filterByName', 'filterByDescription', 'filterByYear', 'jsonMostSoldTshirtImagesPerMonth'));
    }

    public function catalogo(Request $request): View
    {
        $categories = Category::all();

        $filterByCategory = $request->category ?? '';

        $filterByName = $request->name ?? '';

        $filterByDescription = $request->description ?? '';

        // Apenas imagens que fazem parte do catálogo da loja (não são de clientes)
        $tshirtImageQuery = TshirtImage::query()->whereNull('customer_id');

        if ($filterByCategory !== '') {
            $categoryIds = Category::where('name', 'like', "%$filterByCategory%")->pluck('id');
            $tshirtImageQuery->whereIntegerInRaw('category_id', $categoryIds);
        }

        if ($filterByName !== '') {
            $tshirtImageQuery->where('name', 'like', "%$filterByName%");
        }

        if ($filterByDescription !== '') {
            $tshirtImageQuery->where('description', 'like', "%$filterByDescription%");
        }
        $tshirt_images = $tshirtImageQuery->paginate(12);

        // Caso seja necessário fazer “eager loading” dos relacionamentos (em princípio não é necessário)
        //$tshirt_images = $tshirt_images->with('nomeDaRelação', 'nomeDaRelação', '...')->paginate(10);

        return view('tshirt_images.catalogo', compact('tshirt_images', 'categories', 'filterByCategory', 'filterByName', 'filterByDescription'));
    }

    public function edit(TshirtImage $tshirt_image)
    {
        $categories = Category::all();
        return view('tshirt_images.edit', compact('tshirt_image', 'categories'));
    }


    public function show(TshirtImage $tshirt_image): View
    {
        $categories = Category::all();
        return view('tshirt_images.show', compact('tshirt_image', 'categories'));
    }

    public function showProduto(Request $request, TshirtImage $tshirt_image): View
    {
        try{
            if ($tshirt_image->customer_id !== null){
                if ($request->user()->user_type != 'A'){
                    if($request->user()->customer->id != $tshirt_image->customer_id)
                        abort(403);
                }
            }
        }catch(\Exception $e){
            abort(403);
        }

        $relatedProducts = TshirtImage::query()
            ->where('category_id', '=', $tshirt_image->category_id)
            ->where('id', '!=', $tshirt_image->id)
            ->where('customer_id', '=', null)
            ->limit(4)
            ->get();
        $price = Price::find(1);
        $colors = Color::all();
        return view('tshirt_images.produto', compact('tshirt_image', 'colors', 'price', 'relatedProducts'));
    }

    public function minhasTshirtImages(Request $request): View
    {
        if ($request->user()->email_verified_at == null) {
            return view('auth.verify');
        }
        $tshirt_images = $request->user()->customer->tshirtImages;
        return view('tshirt_images.minhas')->with('tshirt_images', $tshirt_images);
    }

    public function getPrivateTshirtImage(Request $request)
    {
        $image_url = $request->image_url;
        // Verifica se existe o nome do ficheiro na base de dados
        if ($image_url == null) {
            abort(404);
        }

        $path = storage_path('app/tshirt_images_private/' . $image_url);
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

    public function update(TshirtImageRequest $request, TshirtImage $tshirt_image): RedirectResponse
    {
        $tshirt_image->update($request->validated());
        $url = route('tshirt_images.show', ['tshirt_image' => $tshirt_image]);
        $htmlMessage = "Imagem de Tshirt <a href='$url'>#{$tshirt_image->id}</a>
                        <strong>\"{$tshirt_image->name}\"</strong> foi alterada com sucesso!";
        return redirect()->route('tshirt_images.index')
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', 'success');
    }

    public function create(): View
    {
        $tshirt_image = new TshirtImage();
        $categories = Category::all();
        return view('tshirt_images.create', compact('categories', 'tshirt_image'));
    }

    public function store(TshirtImageRequest $request): RedirectResponse
    {
        try {
            if ($request->user()->customer == null) {
                $formData = $request->validated();
                // O administrador não precisa de inserir o customer_id pois são imagens para o catálogo da loja
                $tshirt_image = DB::transaction(function () use ($formData, $request) {
                    $newTshirtImage = new TshirtImage();
                    $newTshirtImage->description = $formData['description'];
                    $newTshirtImage->name = $formData['name'];
                    $newTshirtImage->category_id = $formData['category_id'];
                    $path = $request->file_image->store('public/tshirt_images');
                    $newTshirtImage->image_url = basename($path);
                    $newTshirtImage->save();
                    return $newTshirtImage;
                });
                $redirect = 'tshirt_images.index';
            } else {
                $formData = $request->validated();
                // Cliente insere tshirt, tem que ter costumer_id mas com categoria null
                $tshirt_image = DB::transaction(function () use ($formData, $request) {
                    $newTshirtImage = new TshirtImage();
                    $newTshirtImage->customer_id = $request->user()->customer->id;
                    $newTshirtImage->description = $formData['description'];
                    $newTshirtImage->name = $formData['name'];
                    $path = $request->file_image->store('tshirt_images_private');
                    $newTshirtImage->image_url = basename($path);
                    $newTshirtImage->save();
                    return $newTshirtImage;
                });
                $redirect = 'tshirt_images.minhas';
            }
            $htmlMessage = "Tshirt <strong>\"{$tshirt_image->name}\"</strong> foi criada com sucesso!";
            $alertType = 'success';
        } catch (\Exception $error) {
            $htmlMessage = "Não foi possível criar a tshirt!";
            $alertType = 'danger';
        }
        return redirect()->route($redirect)
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }

    public function destroy(Request $request, TshirtImage $tshirt_image): RedirectResponse
    {
        try {
            $tshirt_image->delete();
            if ($tshirt_image->image_url && $tshirt_image->customer_id == null) {
                $path = storage_path('app/public/tshirt_images/' . $tshirt_image->image_url);
                File::delete($path);
            } elseif ($tshirt_image->image_url && $tshirt_image->customer_id != null) {
                $path = storage_path('app/tshirt_images_private/' . $tshirt_image->image_url);
                File::delete($path);
            }
            $htmlMessage = "Tshirt <strong>#{$tshirt_image->id} {$tshirt_image->name}</strong> foi eliminada com sucesso!";
            if ($request->user()->customer != null) {
                $redirect = 'tshirt_images.minhas';
            } else {
                $redirect = 'tshirt_images.index';
            }
            return redirect()->route($redirect)
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', 'success');
        } catch (\Exception $error) {
            $url = route('tshirt_images.show', ['tshirt_image' => $tshirt_image]);
            $htmlMessage = "Não foi possível apagar a T-Shirt <a href='$url'>#{$tshirt_image->id}</a>
                        <strong>\"{$tshirt_image->name}\"</strong> porque ocorreu um erro!";
            $alertType = 'danger';
        }
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }
}
