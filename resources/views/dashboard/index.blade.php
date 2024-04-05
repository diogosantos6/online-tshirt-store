@extends('template_admin.layout')

@section('main')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-3">
                <div class="bg-white rounded d-flex align-items-center justify-content-between p-4">
                    <i class="text-primary" data-feather="trending-up" style="height:53px; width:52px"></i>
                    <div class="ms-3">
                        <p class="mb-2">Vendas de Hoje</p>
                        <h6 class="mb-0">{{ $todayOrders }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-white rounded d-flex align-items-center justify-content-between p-4">
                    <i class="text-primary" data-feather="bar-chart-2" style="height:53px; width:52px"></i>
                    <div class="ms-3">
                        <p class="mb-2">Vendas Totais</p>
                        <h6 class="mb-0">{{ $totalOrders }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-white rounded d-flex align-items-center justify-content-between p-4">
                    <i class="text-primary" data-feather="bar-chart" style="height:53px; width:52px"></i>
                    <div class="ms-3">
                        <p class="mb-2">Receita de Hoje</p>
                        <h6 class="mb-0">{{ $todayRevenue }}€</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-white rounded d-flex align-items-center justify-content-between p-4">
                    <i class="text-primary" data-feather="pie-chart" style="height:53px; width:52px"></i>
                    <div class="ms-3">
                        <p class="mb-2">Receita Total</p>
                        <h6 class="mb-0">{{ $totalRevenue }}€</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-white text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Numero de Vendas</h6>
                        <form id="formGraph" method="GET" class="form prevent-scroll"
                            action="{{ route('dashboard.index') }}">
                            {{-- Input hidden para mandar a variável para o javascript --}}
                            <input type="hidden" id="jsonOrdersPerMonth" value="{{ $jsonOrdersPerMonth }}">
                            <select class="form-select-sm " name="year" id="year"
                                onChange="document.getElementById('formGraph').submit()">
                                <option value="" {{ old('year', $filterByYearOrders) === '' ? 'selected' : '' }}>All
                                </option>
                                @for ($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}"
                                        {{ old('year', $filterByYearOrders) == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </form>
                    </div>
                    <div class="card-body py-3">
                        <div class="chart chart-sm">
                            <canvas id="line-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="bg-white text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Receita</h6>
                        <form id="formGraph2" method="GET" class="form prevent-scroll"
                            action="{{ route('dashboard.index') }}">
                            {{-- Input hidden para mandar a variável para o javascript --}}
                            <input type="hidden" id="jsonRevenuePerMonth" value="{{ $jsonRevenuePerMonth }}">
                            <select class="form-select-sm " name="year" id="year"
                                onChange="document.getElementById('formGraph2').submit()">
                                <option value="" {{ old('year', $filterByYearRevenue) === '' ? 'selected' : '' }}>All
                                </option>
                                @for ($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}"
                                        {{ old('year', $filterByYearRevenue) == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </form>
                    </div>
                    <div class="card-body py-3">
                        <div class="chart chart-sm">
                            <canvas id="line-chart2"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-4 px-4">
        <div class="bg-white text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Recent Sales</h6>
                <div class="card-header d-flex align-items-center justify-content-start">
                    <form id="formFilters" method="GET" class="form" action="{{ route('dashboard.index') }}">

                        <select class="form-select-sm" name="status"
                            onChange="document.getElementById('formFilters').submit()">
                            <option value="" {{ old('status', $filterByStatus) === '' ? 'selected' : '' }}>Todos os
                                Estados</option>
                            <option value="closed" {{ old('status', $filterByStatus) === 'closed' ? 'selected' : '' }}>
                                Fechado</option>
                            <option value="paid" {{ old('status', $filterByStatus) === 'paid' ? 'selected' : '' }}>Pago
                            </option>
                            <option value="pending"{{ old('status', $filterByStatus) === 'pending' ? 'selected' : '' }}>
                                Pendente</option>
                            <option value="canceled"{{ old('status', $filterByStatus) === 'canceled' ? 'selected' : '' }}>
                                Cancelado</option>
                        </select>

                        <input type="text" name="customer" class="form-select-sm rounded mr-0"
                            placeholder="Pesquisar por cliente" value="{{ old('customer', $filterByCustomer) }}" />

                        <button type="submit" class="btn m-0 p-1">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">Data</th>
                            <th scope="col">Cliente Id</th>
                            <th scope="col">Cliente Nome</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Detalhes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->date }}</td>
                                <td>{{ $order->customer_id }}</td>
                                <td>{{ $order->customer->user->name ?? 'null' }}</td>
                                <td>{{ $order->total_price }}€</td>
                                <td>
                                    @if ($order->status == 'closed')
                                        <span class="badge bg-success">{{ $order->status }}</span>
                                    @elseif ($order->status == 'canceled')
                                        <span class="badge bg-danger">{{ $order->status }}</span>
                                    @elseif ($order->status == 'paid')
                                        <span class="badge bg-info">{{ $order->status }}</span>
                                    @elseif ($order->status == 'pending')
                                        <span class="badge bg-warning">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td><a class="btn btn-sm btn-primary"
                                        href="{{ route('orders.show', ['order' => $order]) }}">Detalhes</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
