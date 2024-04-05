@extends('template_admin.layout')

@section('main')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Análise</strong> Cores T-Shirts</h1>
        <div class="row">
            <div class="col-xl-6 col-xxl-7">
                <div class="card flex-fill w-100">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title mb-0">Cores mais vendidas</h5>
                            <form id="formGraph" method="GET" class="form prevent-scroll"
                                action="{{ route('colors.index') }}">
                                <input type="hidden" id="jsonMostSoldColorsPerMonth"
                                    value="{{ $jsonMostSoldColorsPerMonth }}">
                                <select class="form-select-sm " name="year" id="year"
                                    onChange="document.getElementById('formGraph').submit()">
                                    <option value="" {{ old('year', $filterByYear) === '' ? 'selected' : '' }}>All
                                    </option>
                                    @for ($year = date('Y'); $year >= 2020; $year--)
                                        <option value="{{ $year }}"
                                            {{ old('year', $filterByYear) == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="chart chart-sm">
                            <canvas id="chartjs-top-colors"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-8 col-xxl-9 w-100">
                <div class="card flex-fill d-flex">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Cores T-Shirt</h5>
                        <div>
                            <form id="formFilters" method="GET" class="form prevent-scroll"
                                action="{{ route('colors.index') }}">
                                <input type="text" name="name" class="form-select-sm rounded mr-0"
                                    placeholder="Pesquisar por nome" value="{{ old('name', $filterByName) }}" />
                                <input type="text" name="code" class="form-select-sm rounded mr-0"
                                    placeholder="Pesquisar por código" value="{{ old('code', $filterByCode) }}" />
                                <button type="submit" class="btn m-0 p-1">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <table class="table table-hover my-0">
                        <thead>
                            <tr>
                                <th class="d-none d-xl-table-cell">Code</th>
                                <th class="d-none d-xl-table-cell">Nome</th>
                                <th class="d-none d-xl-table-cell">Cor T-shirt</th>
                                <th class="d-none d-xl-table-cell"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($colors->count() == 0)
                                <tr>
                                    <td colspan="6" class="text-center">Não existem cores de T-Shirt</td>
                                </tr>
                            @endif
                            @foreach ($colors as $color)
                                <tr>
                                    <td class="d-none d-xl-table-cell">{{ $color->code }}</td>
                                    <td class="d-none d-xl-table-cell">{{ $color->name }}</td>
                                    <td><img src="{{ $color->fullImageUrl }}" alt="Image" class="avatar img-fluid"></td>
                                    <td class="text-end">
                                        <button type="button" name="delete" class="btn btn-danger mx-1" title="Eliminar"
                                            data-bs-toggle="modal" data-bs-target="#confirmationModal"
                                            data-msgLine1="Quer realmente apagar a cor <strong>&quot;{{ $color->name }}&quot;</strong>?"
                                            data-action="{{ route('colors.destroy', ['color' => $color]) }}">
                                            <i data-feather="trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="pagination-container">
                {{ $colors->withQueryString()->links() }}
            </div>
        </div>
    </div>
    @include('shared.confirmationDialog', [
        'title' => 'Apagar Cor de T-Shirt',
        'msgLine1' => 'As alterações efetuadas ao dados da cor vão ser perdidas!',
        'msgLine2' => 'Clique no botão "Apagar" para confirmar a operação.',
        'confirmationButton' => 'Apagar Cor de T-Shirt',
        'formMethod' => 'DELETE',
    ])
@endsection
