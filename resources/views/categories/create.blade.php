@extends('template_admin.layout')

@section('main')
    <div class="container-fluid p-0">
        <div class="mb-2">
            <a href="javascript:void(0);" onclick="javascript:history.back();">
                <i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i>
            </a>
        </div>
        <div class="row">
            <h1 class="h3 mb-3"><strong>Criar</strong> nova categoria</h1>
        </div>
        <form id="form_tshirt_images" novalidate class="needs-validation" method="POST" action="{{ route('categories.store') }}"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card p-2">
                        <div class="card-body">
                            <span>Nome</span>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('nome') }}" required>
                                @error('name')
                                    <br>
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            <br>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary" name="ok">Criar</button>
                                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
