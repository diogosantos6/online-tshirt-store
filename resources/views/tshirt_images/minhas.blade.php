@extends('template.layout')

@section('main')
    <section class="py-2">
        <div class="container px-4 px-lg-5 mt-5">
            <form id="form_tshirt_images" class="needs-validation" method="POST" action="{{ route('tshirt_images.store') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="mb-3 form-floating">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        id="inputName" value="{{ old('name') }}">
                    <label for="inputName" class="form-label">Nome</label>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3 form-floating">
                    <input type="text" class="form-control @error('description') is-invalid @enderror" name="description"
                        id="inputDescription" value="{{ old('description') }}">
                    <label for="inputDescription" class="form-label">Descrição</label>
                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3 pt-3">
                    <input type="file" class="form-control @error('file_image') is-invalid @enderror" name="file_image"
                        id="inputFileImage">
                    @error('file_image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button class="btn btn-outline-dark flex-shrink-0" type="submit" name="form_tshirt_images">
                    Enviar Imagem
                </button>
            </form>
        </div>
    </section>


    <section class="py-2">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                @foreach ($tshirt_images as $tshirt_image)
                    <div class="col mb-5">
                        <div class="card h-100">
                            <div class="card-height">
                                <!-- Product image-->
                                <div class="image-container">
                                    <img class="card-img-top max-height-img" id="tshirt-color"
                                        src="/storage/tshirt_base/fafafa.jpg" alt="Background Image" />
                                    <img class="card-img-top max-height-img overlay-image"
                                        src="{{ $tshirt_image->fullImageUrl }}">
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
                                        image</a>
                                    <button type="button" name="delete" class="btn btn-outline-danger mt-auto"
                                        title="Eliminar" data-bs-toggle="modal" data-bs-target="#confirmationModal"
                                        data-msgLine1="Quer realmente apagar a imagem <strong>&quot;{{ $tshirt_image->name }}&quot;</strong>?"
                                        data-action="{{ route('tshirt_images.destroy', ['tshirt_image' => $tshirt_image]) }}">
                                        <i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @include('shared.confirmationDialog', [
        'title' => 'Apagar Imagem de T-Shirt',
        'msgLine2' => 'Clique no botão "Apagar" para confirmar a operação.',
        'confirmationButton' => 'Apagar',
        'formMethod' => 'DELETE',
    ])
@endsection
