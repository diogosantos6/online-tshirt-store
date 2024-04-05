@extends('template.layout')

@section('titulo', 'Cliente')

@section('subtitulo')
    <ol class="breadcrumb" style="padding-left: 1.5%">
        <li class="breadcrumb-item">Clientes</li>
        <li class="breadcrumb-item"><strong>{{ $customer->user->name }}</strong></li>
        <li class="breadcrumb-item active">Consultar</li>
    </ol>
@endsection

@section('main')
    <div class="container py-4">
        <div class="d-flex flex-column flex-sm-row justify-content-start align-items-start">
            <div class="flex-grow-1 pe-2">
                @include('customers.fields', ['customer' => $customer, 'readonlyData' => true])
                <div class="my-1 d-flex justify-content-end">
                    <button type="button" name="delete" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#confirmationModal"
                        data-msgLine1="Quer realmente apagar a conta <strong>&quot;{{ $customer->user->name }}&quot;</strong>?"
                        data-action="{{ route('customers.destroy', ['customer' => $customer]) }}">
                        Apagar Conta
                    </button>
                    @if ((Auth::user()->user_type ?? '') == 'A')
                        <a href="{{ route('customers.index') }}" class="btn btn-primary ms-3">Voltar</a>
                    @else
                        <a href="{{ route('customers.edit', ['customer' => $customer]) }}" class="btn btn-secondary ms-3">
                            Alterar Dados
                        </a>
                    @endif
                </div>
            </div>
            <div class="ps-2 mt-5 mt-md-1 d-flex mx-auto flex-column align-items-center justify-content-between"
                style="min-width:260px; max-width:260px;">
                @include('customers.fields_foto', [
                    'customer' => $customer,
                    'allowUpload' => false,
                    'allowDelete' => false,
                ])
            </div>
        </div>
    </div>
    @include('shared.confirmationDialog', [
        'title' => 'Apagar Cliente',
        'confirmationButton' => 'Apagar',
        'formMethod' => 'DELETE',
    ])

@endsection
