@extends('template.layout')

@section('main')
    <section class="h-100 h-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12">
                    <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                        <div class="card-body p-0">
                            <div class="row g-0">
                                <div class="col-lg-8">
                                    <div class="p-5">
                                        <div class="d-flex justify-content-between align-items-center mb-5">
                                            <h1 class="fw-bold mb-0 text-black">Shopping Cart</h1>
                                            <h6 class="mb-0 text-muted">{{ count($cart) . ' items' }}</h6>
                                        </div>
                                        <hr class="my-4">
                                        @foreach ($cart as $orderItem)
                                            <div class="row mb-4 d-flex align-items-center justify-content-between">
                                                <div class="col-md-2 col-lg-2 col-xl-2">
                                                    <div class="image-container">
                                                        <img class="card-img-top max-height-img" id="tshirt-color"
                                                            src="{{ $orderItem->color->fullimageUrl ?? asset('/img/plain_white.png') }}"
                                                            alt="Background Image" />
                                                        {{-- src="/storage/tshirt_base/{{ $orderItem->color->code }}.jpg" --}}
                                                        <img class="card-img-top max-height-img overlay-image"
                                                            src="{{ $orderItem->tshirtImage->fullImageUrl }}"
                                                            alt="Overlay Image" />
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <h6 class="text-muted"> {{ $orderItem->tshirtImage->name }}</h6>
                                                    <h6 class="text-black mb-0"> {{ $orderItem->color->name }}</h6>
                                                </div>
                                                <div
                                                    class="col-md-3 col-lg-3 col-xl-2 d-flex align-items-center justify-content-between">

                                                    <form method="POST" action="{{ route('cart.updateItemQuantity') }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="minusQty"
                                                            value="{{ json_encode($orderItem) }}">

                                                        <button type="submit" class="btn px">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </form>

                                                    <h6 class="text-black mb-0 form-control bg-light border-secondary">
                                                        {{ $orderItem->qty }} </h6>

                                                    <form method="POST" action="{{ route('cart.updateItemQuantity') }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="plusQty"
                                                            value="{{ json_encode($orderItem) }}">
                                                        <button type="submit" class="btn px">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </form>

                                                </div>
                                                <div class="col-md-1">
                                                    <h6 class="text-muted">Size</h6>
                                                    <h6 class="text-black mb-0"> {{ $orderItem->size }}</h6>
                                                </div>

                                                <div class="col-md-2 text-nowrap">
                                                    <h6 class="text-muted">Preço Unitario</h6>
                                                    <h6 class="text-black mb-0">{{ $orderItem->unit_price . '€' }}</h6>
                                                </div>

                                                <div class="col-md-2">
                                                    <h6 class="text-muted">Subtotal</h6>
                                                    <h6 class="text-black mb-0">{{ $orderItem->sub_total . '€' }}</h6>
                                                </div>

                                                <div class="col-md-1">
                                                    <form method="POST" action="{{ route('cart.remove') }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="item"
                                                            value="{{ json_encode($orderItem) }}">
                                                        <button type="submit" class="btn text-muted"><i
                                                                class="fas fa-times"></i></button>
                                                    </form>
                                                    {{-- POST para esconder o URL (deve de haver outra forma para fazer isso)) --}}
                                                    <form method="POST" action="{{ route('cart.editCartItem') }}">
                                                        @csrf
                                                        <input type="hidden" name="item"
                                                            value="{{ json_encode($orderItem) }}">
                                                        <button type="submit" class="btn text-muted"><i
                                                                class="bi bi-pencil-fill"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                            <hr class="my-4">
                                        @endforeach
                                        <div class="pt-5">
                                            <h6 class="mb-0"><a href="{{ route('tshirt_images.catalogo') }}"
                                                    class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Back to
                                                    shop</a>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 bg-grey">
                                    <div class="p-5">
                                        <h3 class="fw-bold mb-5 mt-2 pt-1">Resumo</h3>
                                        <hr class="my-4">

                                        <div class="d-flex justify-content-between mb-4">
                                            <h5 class="text-uppercase">{{ count($cart) . ' items' }}</h5>
                                        </div>
                                        <hr class="my-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <h5 class="text-uppercase">Preço</h5>
                                            <h5>
                                                @if ($originalPrice == 0)
                                                    <h5>-</h5>
                                                @else
                                                    <h5> {{ number_format($originalPrice, 2, ',', '.') . '€' }}</h5>
                                                @endif
                                            </h5>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <h5 class="text-uppercase">Desconto</h5>
                                            <h5>
                                                @if ($originalPrice - $total == 0)
                                                    <h5>-</h5>
                                                @else
                                                    <h5>{{ number_format($originalPrice - $total, 2, ',', '.') . '€' }}
                                                    </h5>
                                                @endif
                                            </h5>
                                        </div>
                                        <hr class="my-4">
                                        <div class="d-flex justify-content-between mb-5">
                                            <h5 class="text-uppercase">Preço Final</h5>
                                            <h5>
                                                @if ($total == 0)
                                                    <h5>-</h5>
                                                @else
                                                    <h5>{{ number_format($total, 2, ',', '.') . '€' }}</h5>
                                                @endif
                                            </h5>
                                        </div>
                                        <a href="{{ route('cart.confirmar') }}">
                                            <button class="btn btn-dark btn-block btn-lg"
                                                data-mdb-ripple-color="dark">Confirmar</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
