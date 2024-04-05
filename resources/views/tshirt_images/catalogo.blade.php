@extends('template.layout')

@section('main')
    <div class="p-0 m-0">
        <header class="py-5 header_background text-center p-0 m-0">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-left text-white">
                    <h1 class="display-4 fw-bolder">ImagineShirt</h1>
                    <p class="lead fw-normal text-white-50 mb-0">Catálogo</p>
                </div>
            </div>
        </header>

        <section class="py-2 filter-section m-0 p-0">
            <form method="GET" id="formFilters" class="form" action="{{ route('tshirt_images.catalogo') }}">
                <div class="container px-4 px-lg-5 mt-5 filter_container">
                    <ul class="me-auto mb-2 mb-lg-0 ms-lg-4 ul_filters">
                        <li class="dropdown li_filter">
                            <a class="dropdown-toggle btn dropdown-btn" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">Nome</a>
                            <ul class="dropdown-menu dropdown_ul">
                                <li class="li_drop">
                                    <div class="input-group rounded">
                                        <input type="text" name="name" class="form-control rounded filter_txtinput"
                                            placeholder="Pesquisar por Nome" aria-label="Search"
                                            aria-describedby="search-addon" value="{{ old('name', $filterByName) }}" />
                                        <button type="submit" class="btn">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown li_filter input_group form-outline">
                            <a class="dropdown-toggle btn dropdown-btn" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">Descrição</a>
                            <ul class="dropdown-menu dropdown_ul">
                                <div class="input-group rounded">
                                    <input type="text" name="description" class="form-control rounded filter_txtinput"
                                        placeholder="Pesquisar por Descrição" aria-label="Search"
                                        aria-describedby="search-addon"
                                        value="{{ old('description', $filterByDescription) }}" />
                                    <button type="submit" class="btn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </ul>
                        </li>
                        <li class="dropdown li_filter input_group form-outline">
                            <select class="form-select" name="category"
                                onChange="document.getElementById('formFilters').submit()">
                                <option value="" {{ old('category', $filterByCategory) === '' ? 'selected' : '' }}>
                                    Todos as Categorias</option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}"
                                        {{ old('category', $filterByCategory) == $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                @endforeach
                            </select>
                        </li>
                    </ul>
                </div>
            </form>
        </section>

        <section class="py-2 p-0 m-0">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    @foreach ($tshirt_images as $tshirt_image)
                        <div class="col mb-4">
                            <div class="card h-100">
                                <div class="card-height">
                                    <!-- Product image-->
                                    <div class="image-container">
                                        <img class="card-img-top max-height-img" id="tshirt-color"
                                            src="/storage/tshirt_base/fafafa.jpg" alt="Background Image" />
                                        <img class="card-img-top max-height-img overlay-image"
                                            src="{{ $tshirt_image->fullImageUrl }}" alt="Overlay Image" />
                                    </div>
                                </div>
                                <!-- Product details-->
                                <div class="card-body p-4">
                                    <div class="text-center">
                                        <!-- Product name-->
                                        <h5 class="fw-bolder">{{ $tshirt_image->name }}</h5>
                                        <!-- Product description -->
                                        <p class="description">{{ $tshirt_image->description }}</p>
                                    </div>
                                </div>
                                <!-- Product actions-->
                                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                    <div class="text-center"><a class="btn btn-outline-dark mt-auto"
                                            href="{{ route('tshirt_images.produto', ['tshirt_image' => $tshirt_image]) }}">View
                                            image</a></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <section class="py-2">
            <div class="container px-4 px-lg-5 mt-3">
                <div class="pagination-container">
                    {{ $tshirt_images->withQueryString()->links() }}
                </div>
            </div>
        </section>
    </div>
@endsection
