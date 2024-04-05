@extends('template_admin.layout')

@section('main')
    <div class="container-fluid p-0">
        <div class="row mb-2">
            <a href="javascript:void(0);" onclick="javascript:history.back();">
                <i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i>
            </a>
        </div>

        <div class="row">
            <h1 class="h3 mb-3"><strong>Editar</strong> Imagem T-Shirt Nº <b>
                    {{ str_pad($tshirt_image->id, 2, '0', STR_PAD_LEFT) }}
                </b> </h1>
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <img class="card-img-top" src="{{ $tshirt_image->fullImageUrl }}" alt="Unsplash">
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <form id="form_tshirt_images" class="needs-validation" method="POST"
                        action="{{ route('tshirt_images.update', ['tshirt_image' => $tshirt_image]) }}">
                        @csrf
                        @method('PUT')
                        @include('tshirt_images.shared.fields')
                        <div class="card-body text-left">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary" name="ok">Guardar Alterações</button>
                                <a href="{{ route('tshirt_images.show', ['tshirt_image' => $tshirt_image]) }}"
                                    class="btn btn-secondary">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
