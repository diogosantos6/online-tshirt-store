@extends('template.layout')

@section('titulo', 'Alterar Cliente')

@section('subtitulo')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Clientes</li>
        <li class="breadcrumb-item"><strong>{{ $customer->user->name }}</strong></li>
        <li class="breadcrumb-item active">Alterar</li>
    </ol>
@endsection

@section('main')
    <div class="container py-4">
        <form id="form_customer" class="needs-validation" method="POST"
            action="{{ route('customers.update', ['customer' => $customer]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $customer->user->id }}">
            <div class="d-flex flex-column flex-sm-row justify-content-start align-items-start">
                <div class="flex-grow-1 pe-2">
                    @include('customers.fields', ['customer' => $customer, 'readonlyData' => false])
                    <div class="my-1 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" name="ok" form="form_customer">Guardar
                            Alterações</button>
                        <a href="{{ route('customers.show', ['customer' => $customer]) }}"
                            class="btn btn-secondary ms-3">Voltar</a>
                    </div>
                </div>
                <div class="ps-2 mt-5 mt-md-1 d-flex mx-auto flex-column align-items-center justify-content-between"
                    style="min-width:260px; max-width:260px;">
                    @include('customers.fields_foto', [
                        'customer' => $customer,
                        'allowUpload' => true,
                        'allowDelete' => true,
                    ])
                </div>
            </div>
        </form>
    </div>
    @include('shared.confirmationDialog', [
        'title' => 'Apagar fotografia',
        'msgLine1' => 'As alterações efetuadas ao dados do cliente vão ser perdidas!',
        'msgLine2' => 'Clique no botão "Apagar" para confirmar a operação.',
        'confirmationButton' => 'Apagar fotografia',
        'formMethod' => 'DELETE',
    ])
@endsection
