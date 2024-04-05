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
                <div class="col-md-5">
                    <h1 class="display-5 fw-bolder">{{ $tshirt_image->name }}</h1>
                    <div class="fs-4 mb-4">
                        <span>
                            @if ($tshirt_image->customer == null)
                                {{ $price->unit_price_catalog . '€' }}
                            @else
                                {{ $price->unit_price_own . '€' }}
                            @endif
                        </span>
                    </div>
                    <p class="lead">{{ $tshirt_image->description }}</p>
                    <form method="POST" action="{{ route('cart.add', ['tshirt_image' => $tshirt_image]) }}">
                        @csrf
                        @include('tshirt_images.shared.fieldsProduto')
                        <button class="btn btn-outline-dark flex-shrink-0" type="submit" name="addToCart">
                            <i class="bi-cart-fill me-1"></i>
                            Add to cart
                        </button>
                    </form>
                </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Related items section-->
    <section class="py-5 bg-light">
        <div class="container px-4 px-lg-5 mt-5">
            <h2 class="fw-bolder mb-4">Related products</h2>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                {{-- SHOW MESSAGE SAYING THERE ARE NO RELATED PRODUCTS --}}
                @if ($relatedProducts->isEmpty())
                    <div class="d-flex align-center">
                        <h3 class="text-center">There are no related products</h3>
                    </div>
                @endif
                @foreach ($relatedProducts as $product)
                    <div class="col mb-4">
                        <div class="card h-100">
                            <div class="card-height">
                                <!-- Product image-->
                                <div class="image-container">
                                    <img class="card-img-top max-height-img" id="tshirt-color"
                                        src="/storage/tshirt_base/fafafa.jpg" alt="Background Image" />
                                    <img class="card-img-top max-height-img overlay-image"
                                        src="{{ $product->fullImageUrl }}" alt="Overlay Image" />
                                </div>
                            </div>
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">{{ $product->name }}</h5>
                                    <!-- Product description -->
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto"
                                        href="{{ route('tshirt_images.produto', ['tshirt_image' => $product]) }}">View
                                        image</a></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
