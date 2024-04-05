@extends('template.layout')

@section('main')
    <section class="py-2">
        <section class="py-2 p-0 m-0">
            <div class="container px-4 px-lg-5 mt-5">
                <h1 class="fw-bold mb-3 text-black">Minhas Encomendas</h1>
                <div class="d-flex justify-content-between align-items-center mb-5">
                    @if ($orders->count() == 0)
                        <div class="d-flex align-center">
                            <h3 class="text-center">Ainda sem encomendas registadas</h3>
                        </div>
                    @else
                        <table class="table table-hover my-0">
                            <thead>
                                <tr class="bg-dark text-white">
                                    <th>Estado</th>
                                    <th>Data</th>
                                    <th>Preço Total</th>
                                    <th>Tipo Pagamento</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            @if ($order->status == 'closed')
                                                <span class="badge bg-success">{{ $order->status }}</span>
                                            @endif
                                            @if ($order->status == 'canceled')
                                                <span class="badge bg-danger">{{ $order->status }}</span>
                                            @endif
                                            @if ($order->status == 'paid')
                                                <span class="badge bg-info">{{ $order->status }}</span>
                                            @endif
                                            @if ($order->status == 'pending')
                                                <span class="badge bg-warning">{{ $order->status }}</span>
                                            @endif
                                        </td>
                                        <td class="d-none d-xl-table-cell">{{ $order->date }}</td>
                                        <td class="d-none d-xl-table-cell">
                                            {{ number_format($order->total_price, 2, ',', '.') . '€' }}</td>
                                        <td class="d-none d-md-table-cell">{{ $order->payment_type }}</td>
                                        <td class="text-end">
                                            <a class="btn btn-dark" href="{{ route('orders.minha', ['order' => $order]) }}">
                                                <i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </section>
    </section>
@endsection
