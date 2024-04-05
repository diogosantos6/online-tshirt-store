@extends('template_admin.layout')

@section('main')
    <div class="container-fluid p-0">

        <a href="{{ route('tshirt_images.index') }}">
            <i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i>
        </a>


        <div class="row mt-3">
            <h1 class="h3 mb-3"><strong>Detalhes</strong> Imagem T-Shirt Nº <b>
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
                    @include('tshirt_images.shared.fields', ['readonlyData' => true])
                    <div class="card-body text-left">
                        <div class="mb-3">
                            <button type="button" name="delete" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#confirmationModal"
                                data-msgLine1="Quer realmente apagar a imagem <strong>&quot;{{ $tshirt_image->name }}&quot;</strong>?"
                                data-action="{{ route('tshirt_images.destroy', ['tshirt_image' => $tshirt_image]) }}">
                                Eliminar
                            </button>
                            <a href="{{ route('tshirt_images.edit', ['tshirt_image' => $tshirt_image]) }}"
                                class="btn btn-primary">Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('shared.confirmationDialog', [
        'title' => 'Apagar Imagem T-Shirt',
        'msgLine1' => 'As alterações efetuadas ao dados da imagem vão ser perdidas!',
        'msgLine2' => 'Clique no botão "Apagar" para confirmar a operação.',
        'confirmationButton' => 'Apagar Imagem T-Shirt',
        'formMethod' => 'DELETE',
    ])
@endsection
