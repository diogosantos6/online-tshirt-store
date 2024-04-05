@extends('template_admin.layout')

@section('main')
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3"><strong>Análise</strong> Encomendas</h1>

        <div class="row">
            <div class="col-xl-6 col-xxl-5 d-flex">
                <div class="w-100">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mt-0">
                                            <h5 class="card-title">Encomendas Fechadas</h5>
                                        </div>

                                        <div class="col-auto">
                                            <div class="stat text-primary">
                                                <i class="align-middle" data-feather="truck"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="mt-1 mb-3">{{ $closedOrders }}</h1>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mt-0">
                                            <h5 class="card-title">Encomendas Pagas</h5>
                                        </div>

                                        <div class="col-auto">
                                            <div class="stat text-primary">
                                                <i class="align-middle" data-feather="truck"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="mt-1 mb-3">{{ $paidOrders }}</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mt-0">
                                            <h5 class="card-title">Encomendas Pendentes</h5>
                                        </div>

                                        <div class="col-auto">
                                            <div class="stat text-primary">
                                                <i class="align-middle" data-feather="truck"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="mt-1 mb-3">{{ $pendingOrders }}</h1>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mt-0">
                                            <h5 class="card-title">Encomendas Canceladas</h5>
                                        </div>

                                        <div class="col-auto">
                                            <div class="stat text-primary">
                                                <i class="align-middle" data-feather="truck"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="mt-1 mb-3">{{ $canceledOrders }}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-xxl-7">
                <div class="card flex-fill w-100">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title mb-0">Encomendas fechadas por mês</h5>
                            <form id="formGraph" method="GET" class="form prevent-scroll"
                                action="{{ route('orders.index') }}">
                                {{-- Input hidden para mandar a variável para o javascript --}}
                                <input type="hidden" id="jsonClosedOrdersPerMonth" value="{{ $jsonClosedOrdersPerMonth }}">
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
                            <canvas id="chartjs-dashboard-line"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-8 col-xxl-9 w-100">
                <div class="card flex-fill d-flex">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Encomendas</h5>
                        <div>
                            <form id="formFilters" method="GET" class="form prevent-scroll"
                                action="{{ route('orders.index') }}">

                                <select class="form-select-sm" name="status"
                                    onChange="document.getElementById('formFilters').submit()">
                                    <option value="" {{ old('status', $filterByStatus) === '' ? 'selected' : '' }}>
                                        Todos
                                        os Estados</option>
                                    <option value="paid"
                                        {{ old('status', $filterByStatus) === 'paid' ? 'selected' : '' }}>
                                        Estado Pago</option>
                                    <option value="pending"
                                        {{ old('status', $filterByStatus) === 'pending' ? 'selected' : '' }}>Estado
                                        Pendente
                                    </option>
                                    @if($userType == 'A')
                                        <option value="canceled"
                                            {{ old('status', $filterByStatus) === 'canceled' ? 'selected' : '' }}>Estado
                                            Cancelado
                                        </option>
                                        <option value="closed"
                                            {{ old('status', $filterByStatus) === 'closed' ? 'selected' : '' }}>
                                            Estado Fechado
                                        </option>
                                    @endif
                                </select>
                                <input type="date" id="date" name="date" class="form-select-sm"
                                    value="{{ old('date', $filterByDate) }}"
                                    onChange="document.getElementById('formFilters').submit()">

                                <input type="text" name="customer" class="form-select-sm rounded mr-0"
                                    placeholder="Pesquisar por cliente" value="{{ old('customer', $filterByCustomer) }}" />

                                <button type="submit" class="btn m-0 p-1">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <table class="table table-hover my-0">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th class="d-none d-xl-table-cell">ID cliente</th>
                                <th class="d-none d-xl-table-cell">Nome cliente</th>
                                <th class="d-none d-xl-table-cell">Data</th>
                                <th>Preço Total</th>
                                <th class="d-none d-md-table-cell">Tipo Pagamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->count() == 0)
                                <tr>
                                    <td colspan="6" class="text-center">Não existem encomendas</td>
                                </tr>
                            @endif
                            @foreach ($orders as $order)
                                <tr onClick="window.location='{{ route('orders.show', ['order' => $order]) }}'"
                                    class="cursor-pointer">
                                    <td>
                                        @switch($order->status)
                                            @case('closed')
                                                <span class="badge bg-success">{{ $order->status }}</span>
                                            @break

                                            @case('canceled')
                                                <span class="badge bg-danger">{{ $order->status }}</span>
                                            @break

                                            @case('paid')
                                                <span class="badge bg-info">{{ $order->status }}</span>
                                            @break

                                            @case('pending')
                                                <span class="badge bg-warning">{{ $order->status }}</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="d-none d-xl-table-cell">{{ $order->customer_id }}</td>
                                    {{-- TODO --}}
                                    <td class="d-none d-xl-table-cell">
                                        {{ $order->customer->user->name ?? 'null' }}
                                    </td>
                                    <td class="d-none d-xl-table-cell">{{ $order->date }}</td>
                                    <td>{{ $order->total_price }}</td>
                                    <td class="d-none d-md-table-cell">{{ $order->payment_type }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="pagination-container">
                {{ $orders->withQueryString()->links() }}
            </div>
        </div>

    </div>
@endsection
