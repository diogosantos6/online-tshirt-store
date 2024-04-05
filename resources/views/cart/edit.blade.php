@extends('template.layout')

@section('main')
    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="col-md-6">
                    <div class="image-container">
                        <img class="card-img-top mb-5 mb-md-0" id="tshirt-color" src="/storage/tshirt_base/fafafa.jpg"
                            alt="Background Image" />
                        <img class="card-img-top mb-5 mb-md-0 overlay-image" src="{{ $tshirt_image->fullImageUrl }}"
                            alt="Overlay Image" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="small mb-1">SKU: BST-498</div>
                    <h1 class="display-5 fw-bolder">{{ $tshirt_image->name }}</h1>
                    <div class="fs-5 mb-5">
                        <span class="text-decoration-line-through">$45.00</span>
                        <span>$40.00</span>
                    </div>
                    <p class="lead">{{ $tshirt_image->description }}</p>
                    <form method="POST" action="{{ route('cart.update', ['tshirt_image' => $tshirt_image]) }}">
                        @csrf
                        @method('PUT')
                        @include('tshirt_images.shared.fieldsProduto')
                        <input type="hidden" name="item" value="{{ json_encode($orderItem) }}">
                        <button class="btn btn-outline-dark flex-shrink-0" type="submit" name="updateCartItem">
                            <i class="bi-cart-fill me-1"></i>
                            Confirmar Edição
                        </button>
                    </form>
                </div>
                </form>
            </div>
        </div>
    </section>
@endsection
