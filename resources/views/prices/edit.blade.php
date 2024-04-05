@extends('template_admin.layout')

@section('main')
    <div class="container-fluid p-0">
        <div class="row mb-2">
            <a href="javascript:void(0);" onclick="javascript:history.back();">
                <i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i>
            </a>
        </div>

        <div class="row">
            <h1 class="h3 mb-3"><strong>Gerir</strong> Preços</h1>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <form id="form_prices" class="needs-validation" method="POST"
                        action="{{ route('prices.update', ['price' => $price]) }}">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h5 class="card-title mb-0">Preço Imagem Catálogo (€)</h5>
                        </div>
                        <div class="card-body">
                            <input type="number" class="form-control @error('unit_price_catalog') is-invalid @enderror"
                                name="unit_price_catalog" id="inputUnitPriceCatalog"
                                value="{{ old('unit_price_catalog', $price->unit_price_catalog) }}" min="0"
                                step="any">
                            @error('unit_price_catalog')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="card-header">
                            <h5 class="card-title mb-0">Preço Imagem Própria (€)</h5>
                        </div>
                        <div class="card-body">
                            <input type="number" class="form-control @error('unit_price_own') is-invalid @enderror"
                                name="unit_price_own" id="inputUnitPriceOwn"
                                value="{{ old('unit_price_own', $price->unit_price_own) }}" min="0" step="any">
                            @error('unit_price_own')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="card-header">
                            <h5 class="card-title mb-0">Desconto Imagem Catálogo (%)</h5>
                        </div>
                        <div class="card-body">
                            <input type="number"
                                class="form-control @error('unit_price_catalog_discount') is-invalid @enderror"
                                name="unit_price_catalog_discount" id="inputUnitPriceCatalogDiscount"
                                value="{{ old('unit_price_catalog_discount', $price->unit_price_catalog_discount) }}"
                                min="0" step="any">
                            @error('unit_price_catalog_discount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="card-header">
                            <h5 class="card-title mb-0">Desconto Imagem Própria (%)</h5>
                        </div>
                        <div class="card-body">
                            <input type="number"
                                class="form-control @error('unit_price_own_discount') is-invalid @enderror"
                                name="unit_price_own_discount" id="inputUnitPriceOwnDiscount"
                                value="{{ old('unit_price_own_discount', $price->unit_price_own_discount) }}" min="0"
                                step="any">
                            @error('unit_price_own_discount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="card-header">
                            <h5 class="card-title mb-0">Quantidade Para Ativar Desconto</h5>
                        </div>
                        <div class="card-body">
                            <input type="number" class="form-control @error('qty_discount') is-invalid @enderror"
                                name="qty_discount" id="inputQtyDiscount"
                                value="{{ old('qty_discount', $price->qty_discount) }}" min="0">
                            @error('qty_discount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="card-body text-left">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary" name="ok">Guardar Alterações</button>
                                <a href="{{ route('prices.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
