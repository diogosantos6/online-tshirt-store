<?php

namespace App\Http\Controllers;

use App\Http\Requests\ColorRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Color;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ColorController extends Controller
{
    public function index(Request $request): View
    {
        $colorsQuery = Color::query();

        $filterByYear = $request->year ?? '';

        $filterByName = $request->name ?? '';

        $filterByCode = $request->code ?? '';

        $jsonMostSoldColorsPerMonth = DB::table('orders as o')
            ->join('order_items as oi', 'oi.order_id', '=', 'o.id')
            ->join('colors', 'colors.code', '=', 'oi.color_code')
            ->select('oi.color_code', 'colors.name', DB::raw('SUM(oi.qty) as total_sold'))
            ->when($filterByYear, function ($query, $filterByYear) {
                return $query->whereYear('o.date', $filterByYear);
            })
            ->whereIn('o.status', ['closed', 'paid'])
            ->groupBy('oi.color_code', 'colors.name')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();

        if ($filterByName !== '') {
            $colorsQuery->where('name', 'like', "%$filterByName%");
        }

        if ($filterByCode !== '') {
            $colorsQuery->where('code', 'like', "%$filterByCode%");
        }

        $colors = $colorsQuery->paginate(15);

        return view('colors.index', compact('colors', 'jsonMostSoldColorsPerMonth', 'filterByYear', 'filterByName', 'filterByCode'));
    }

    public function destroy(Color $color): RedirectResponse
    {
        try {
            $color->delete();
            if ($color->code) {
                $path = storage_path('app/public/tshirt_base/' . $color->code . '.jpg');
                File::delete($path);
            }
            $htmlMessage = "Cor <strong>#{$color->code} {$color->name}</strong> foi eliminada com sucesso!";
            return redirect()->route('colors.index')
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', 'success');
        } catch (\Exception $error) {
            $htmlMessage = "Não foi possível apagar a cor #{$color->code}</a>
                        <strong>\"{$color->name}\"</strong> porque ocorreu um erro!";
            $alertType = 'danger';
        }
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }
}
