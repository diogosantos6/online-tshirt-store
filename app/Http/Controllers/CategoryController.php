<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\OrderRequest;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $filterByName = $request->name ?? '';
        $filterByYear = $request->year ?? '';

        //Query para o gráfico de categorias mais vendidas
        $bestSellingCategoriesPerMonthQuery = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('tshirt_images', 'order_items.tshirt_image_id', '=', 'tshirt_images.id')
            ->join('categories', 'tshirt_images.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('SUM(order_items.qty) as total_sold'))
            ->whereIn('orders.status', ['closed', 'paid'])
            ->groupBy('categories.name')
            ->orderByRaw('total_sold DESC');

        //Query para obter a proporção de categorias nas imagens
        $tshirt_imagesPerCategory = Category::leftJoin('tshirt_images', 'categories.id', '=', 'tshirt_images.category_id')
            ->select('categories.name', DB::raw('COUNT(tshirt_images.id) as tshirt_count'))
            ->groupBy('categories.name')
            ->orderByRaw('tshirt_count DESC')
            ->take(10)
            ->get();

        //Query para a tabela de encomendas
        $categoryQuery = Category::query();

        if($filterByYear != '') {
            $bestSellingCategoriesPerMonthQuery->whereYear('orders.date', $filterByYear);
        }

        //Filtrar por nome
        if ($filterByName != '') {
            $categoryQuery->where('name', 'LIKE', "%$filterByName%");
        }

        $bestSellingCategoriesPerMonth = $bestSellingCategoriesPerMonthQuery->get();

        //Paginação (tabela)
        $categories = $categoryQuery->paginate(10);

        return view('categories.index', compact('categories', 'filterByName', 'filterByYear', 'bestSellingCategoriesPerMonth', 'tshirt_imagesPerCategory'));
    }

    public function create(): View
    {
        $category = new Category();
        return view('categories.create', compact('category'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        try {
            $formData = $request->validated();
            $category = DB::transaction(function () use ($formData, $request) {
                $newCategory = new Category();
                $newCategory->name = $formData['name'];
                $newCategory->save();
                return $newCategory;
                });

            $htmlMessage = "Categoria <strong>\"{$category->name}\"</strong> foi criada com sucesso!";
            $alertType = 'success';
            $redirect = 'categories.index';
        } catch (\Exception $error) {
            $htmlMessage = "Não foi possível criar a categoria!";
            $alertType = 'danger';
            $redirect = 'categories.index';
        }
        return redirect()->route($redirect)
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }

    public function destroy(Category $category): RedirectResponse
    {
        try {
            $tshirtImagesCount = $category->tshirtImages()->withTrashed()->count();
            if ($tshirtImagesCount > 0) {
                $htmlMessage = "Não foi possível apagar a categoria <strong>{$category->name}</strong> porque existem {$tshirtImagesCount} imagens associadas!";
                $alertType = 'danger';

                return redirect()->route('categories.index')
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            }

            $category->delete();
            $htmlMessage = "Categoria <strong>{$category->name}</strong> foi eliminada com sucesso!";

            return redirect()->route('categories.index')
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', 'success');

        } catch (\Illuminate\Database\QueryException $error) {
            $htmlMessage = "Não foi possível apagar a categoria <strong>{$category->name}</strong> porque ocorreu um erro!";
            $alertType = 'danger';

            return redirect()->back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        }
    }
}
