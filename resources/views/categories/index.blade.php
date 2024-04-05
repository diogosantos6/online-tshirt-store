@extends('template_admin.layout')

@section('main')
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3"><strong>Análise</strong> Categorias</h1>

        <div class="row">

            <div class="col-xl-6 col-xxl-6 d-flex w-100">

                <div class="card flex-fill col-xl-6 w-50">
                    <div class="card-header">

                        <h5 class="card-title mb-0">Proporção de categorias</h5>
                    </div>
                    <div class="card-body d-flex">
                        <div class="align-self-center w-100">
                            <form>
                                <input type="hidden" id="tshirt_imagesPerCategory" value="{{ $tshirt_imagesPerCategory }}">
                            </form>
                            <div class="py-3">
                                <div class="chart chart-xs">
                                    <canvas id="chartjs-dashboard-pie-category"></canvas>
                                </div>
                            </div>

                            <table class="table mb-0">
                                @php $counter = 0 @endphp
                                <tbody>
                                    @foreach ($tshirt_imagesPerCategory as $category)
                                        @if ($counter < 3)
                                            <tr>
                                                <td>{{ $category->name }}</td>
                                                <td class="text-end">{{ $category->tshirt_count }}</td>
                                            </tr>
                                            @php $counter++ @endphp
                                        @else
                                            @break
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- GAP -->
                <div style="width: 20px;"></div>

                <div class="card flex-fill col-xl-6 w-50">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title mb-0">Categorias mais vendidas</h5>
                            <form id="formGraph" method="GET" class="form prevent-scroll"
                                action="{{ route('categories.index') }}">
                                {{-- Input hidden para mandar a variável para o javascript --}}
                                <input type="hidden" id="bestSellingCategoriesPerMonth" value="{{ $bestSellingCategoriesPerMonth }}">
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
                        <div class="chart align-self-center chart chart-lg">
                            <canvas id="chartjs-top-categories"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h1> <a href="{{ route('categories.create') }}" class="btn btn-primary">Adicionar nova categoria</a></h1>

        <div class="row">
            <div class="col-12 col-lg-8 col-xxl-12">
                <div class="card flex-fill d-flex">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Categorias</h5>

                            <form id="formFilters" method="GET" class="form prevent-scroll"
                                action="{{ route('categories.index') }}">

                                <input type="text" name="name" class="form-select-sm rounded mr-0"
                                    placeholder="Pesquisar por nome" value="{{ old('name', $filterByName) }}" />

                                <button type="submit" class="btn m-0 p-1">
                                    <i class="bi bi-search"></i>
                                </button>

                            </form>
                    </div>
                    <table class="table table-hover my-0">
                        <thead>
                            <tr>
                                <th class="d-none d-xl-table-cell">ID</th>
                                <th class="d-none d-xl-table-cell">Nome</th>
                                <th class="d-none d-xl-table-cell"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($categories->count() == 0)
                                <tr>
                                    <td colspan="6" class="text-center">Não existem categorias</td>
                                </tr>
                            @endif
                            @foreach ($categories as $category)
                                <tr class="cursor-pointer">
                                    <td>{{ $category->id }}</td>
                                    <td class="d-none d-xl-table-cell">{{ $category->name }}</td>
                                    <td class="text-end">

                                        <button type="button" name="delete" class="btn btn-danger mx-1" title="Eliminar"
                                            data-bs-toggle="modal" data-bs-target="#confirmationModal"
                                            data-msgLine1="Quer realmente apagar a categoria <strong>&quot;{{ $category->name }}&quot;</strong>?"
                                            data-action="{{ route('categories.destroy', ['category' => $category]) }}">
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
                {{ $categories->withQueryString()->links() }}
            </div>
        </div>

    </div>
    @include('shared.confirmationDialog', [
        'title' => 'Apagar categoria',
        'msgLine1' => 'As alterações efetuadas ao dados vão ser perdidas!',
        'msgLine2' => 'Clique no botão "Apagar" para confirmar a operação.',
        'confirmationButton' => 'Apagar',
        'formMethod' => 'DELETE',
    ])
@endsection
