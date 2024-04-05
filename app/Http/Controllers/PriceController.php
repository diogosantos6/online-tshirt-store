<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceRequest;
use App\Models\Price;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index(): View
    {
        $price = Price::find(1);
        return view('prices.index', compact('price'));
    }

    public function edit(Price $price): View
    {
        return view('prices.edit', compact('price'));
    }

    public function update(PriceRequest $request, Price $price): RedirectResponse
    {
        $price->update($request->validated());
        $htmlMessage = "Os <strong>Preços</strong> foram atualizados com sucesso!";
        return redirect()->route('prices.index')
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', 'success');
    }
}
