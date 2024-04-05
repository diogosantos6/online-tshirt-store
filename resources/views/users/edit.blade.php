@extends('template.layout')

@section('titulo', 'Alterar User')

@section('subtitulo')
    <ol class="breadcrumb" style="padding-left: 1%">
        <li class="breadcrumb-item">Users</li>
        <li class="breadcrumb-item"><strong>{{ $user->name }}</strong></li>
        <li class="breadcrumb-item active">Alterar</li>
    </ol>
@endsection

@section('main')
    <div class="container py-5" style="margin-bottom:17%">
        <form id="form_user" class="needs-validation" method="POST" action="{{ route('users.update', ['user' => $user]) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $user->id }}">
            <div class="d-flex flex-column flex-sm-row justify-content-start align-items-start">
                <div class="flex-grow-1 pe-2">
                    @include('users.fields', ['user' => $user, 'readonlyData' => false])
                    <div class="my-1 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" name="ok" form="form_user">Guardar
                            Alterações</button>
                        <a href="{{ route('users.show', ['user' => $user]) }}" class="btn btn-secondary ms-3">Voltar</a>
                    </div>
                </div>
                <div class="ps-2 mt-5 mt-md-1 d-flex mx-auto flex-column align-items-center justify-content-between"
                    style="min-width:260px; max-width:260px;">
                    @include('users.fields_foto', [
                        'user' => $user,
                        'allowUpload' => true,
                        'allowDelete' => true,
                    ])
                </div>
            </div>
        </form>
    </div>
    @include('shared.confirmationDialog', [
        'title' => 'Apagar fotografia',
        'msgLine1' => 'As alterações efetuadas ao dados do user vão ser perdidas!',
        'msgLine2' => 'Clique no botão "Apagar" para confirmar a operação.',
        'confirmationButton' => 'Apagar fotografia',
        'formMethod' => 'DELETE',
    ])
@endsection
