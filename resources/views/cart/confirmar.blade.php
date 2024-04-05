@extends('template.layout')


@section('main')

    <body class="bg-light">
        <div class="container">
            <div class="py-5 text-center">
                <h2>Checkout</h2>
            </div>
            <div class="row">
                <div class="col-md-4 order-md-2 mb-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Your cart</span>
                        <span class="badge badge-secondary badge-pill">3</span>
                    </h4>
                    <ul class="list-group mb-3">
                        @foreach ($cart as $item)
                            <li class="list-group-item d-flex align-items-center justify-content-between lh-condensed">
                                <div class="col-md-2 col-lg-2 col-xl-2">
                                    <div class="image-container">
                                        <img class="card-img-top max-height-img" id="tshirt-color"
                                            src="/storage/tshirt_base/{{ $item->color->code }}.jpg"
                                            alt="Background Image" />
                                        <img class="card-img-top max-height-img overlay-image"
                                            src="{{ $item->tshirtImage->fullImageUrl }}" alt="Overlay Image" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-xl-2 text-center text-nowrap">
                                    <h6 class="my-0">{{ $item->tshirtImage->name }}</h6>
                                    <small class="text-muted">{{ $item->color->name . ' - ' . $item->size }}</small>
                                </div>
                                <div class="col-md-1 col-lg-2 col-xl-2 text-center">
                                    <h6 class="my-0">Qtd</h6>
                                    <small class="text-muted">{{ $item->qty }}</small>
                                </div>
                                <div class="col-md-1">
                                    <h6 class="my-0">Price</h6>
                                    <span class="text-muted">{{ $item->unit_price . '€' }}</span>
                                </div>
                            </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (EUR)</span>
                            <strong>{{ $total . '€' }}</strong>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8 order-md-1">
                    <h4 class="mb-3">Billing address</h4>
                    <form method="POST" action="{{ route('cart.store') }}">
                        @csrf
                        <div class="row">
                            <div class="mb-3">
                                <label for="address">Address</label>
                                <input name="address" type="text"
                                    class="form-control @error('address') is-invalid @enderror" id="address"
                                    value="{{ old('address', $customer->address) }}">
                                @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 ">
                                <label for="email">Notas <span class="text-muted">(Optional)</span></label>
                                <input name="notes" type="text" value="{{ old('notes') }}"
                                    class="form-control @error('notes') is-invalid @enderror" id="notes" placeholder="">
                                @error('notes')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <hr class="mb-4">
                        <div class="row">
                            <h4 class="mb-3">Payment</h4>
                            <div class="d-block my-3 form-control @error('payment_type') is-invalid @enderror">
                                <div class="custom-control custom-radio">
                                    <input id="credit" name="payment_type" type="radio" class="custom-control-input"
                                        value="VISA"
                                        {{ old('payment_type', $customer->default_payment_type) == 'VISA' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="credit">Cartão de crédito VISA</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input id="debit" name="payment_type" type="radio" class="custom-control-input"
                                        value="MC"
                                        {{ old('payment_type', $customer->default_payment_type) == 'MC' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="debit">Cartão de crédito Master Card</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input id="paypal" name="payment_type" type="radio" class="custom-control-input"
                                        value="PAYPAL"
                                        {{ old('payment_type', $customer->default_payment_type) == 'PAYPAL' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="paypal">Paypal</label>
                                </div>
                            </div>
                            @error('payment_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cc-number">Referência de pagamento</label>
                                <input name="payment_ref" type="text"
                                    class="form-control @error('payment_ref') is-invalid @enderror" id="payment_ref"
                                    placeholder="" value="{{ old('payment_ref', $customer->default_payment_ref) }}">
                                @error('payment_ref')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cc-number">NIF</label>
                                <input name="nif" type="text" class="form-control @error('nif') is-invalid @enderror"
                                    id="nif" placeholder="" value="{{ old('nif', $customer->nif) }}">
                                @error('nif')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <hr class="mb-4">
                        <input type="hidden" name="total" value="{{ $total }}">
                        <button class="btn btn-dark btn-lg btn-block">Finalizar encomenda</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
@endsection
