@extends('template.layout')

@section('main')
    <section class="py-2">
        <section class="h-100 gradient-custom">
            <div class="container py-5 h-100">
                <h1 class="fw-bold mb-3 text-center text-black">Detalhe Encomenda</h1>
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-lg-10 col-xl-8">
                        <div class="card" style="border-radius: 10px;">
                            <div class="card-header px-4 py-5">
                                @switch($order->status)
                                    @case('closed')
                                        <h5 class="text-muted mb-0">Obrigado pela sua compra, <span
                                                class="text-black">{{ $order->customer->user->name ?? 'null' }}</span>!
                                        </h5>
                                    @break

                                    @case('canceled')
                                        <h5 class="text-muted mb-0">Encomenda Cancelada</h5>
                                    @break

                                    @case('paid')
                                        <h5 class="text-muted mb-0">Agradecemos o seu pagamento! Após a confirmação da sua
                                            encomenda, iremos processá-la com a máxima brevidade possível.
                                        </h5>
                                    @break

                                    @case('pending')
                                        <h5 class="text-muted mb-0">Encomenda Pendente, efetue o pagamento para que a mesma seja
                                            processada.
                                        </h5>
                                    @break
                                @endswitch
                            </div>
                            <div class="card-body p-4">
                                @foreach ($order->orderItems as $item)
                                    <div class="card shadow-0 border mb-4">

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="image-container">
                                                        <img class="card-img-top max-height-img" id="tshirt-color"
                                                            src="{{ $item->color->fullimageUrl }}" alt="" />
                                                        <img class="card-img-top max-height-img overlay-image"
                                                            src="{{ $item->tshirtImage->fullImageUrl }}" alt="" />
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                    <p class="text-muted mb-0">
                                                        {{ $item->tshirtImage->name }}</p>
                                                </div>
                                                <div
                                                    class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                    <p class="text-muted mb-0 small">{{ $item->color->name }}</p>
                                                </div>
                                                <div
                                                    class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                    <p class="text-muted mb-0 small">Qtd: {{ $item->qty }}</p>
                                                </div>
                                                <div
                                                    class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                    <p class="text-muted mb-0 small">Preço Unitário:
                                                        {{ $item->unit_price . '€' }}
                                                    </p>
                                                </div>
                                                <div
                                                    class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                    <p class="text-muted mb-0 small">Preço Total:
                                                        {{ $item->sub_total . '€' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-between pt-2">
                                    <p class="fw-bold mb-0">Order Details</p>
                                </div>

                                <div class="d-flex justify-content-between pt-2">
                                    <p class="text-muted mb-0">Número Encomenda: {{ $item->order->id }}</p>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <p class="text-muted mb-0">Data do Pedido: {{ $item->order->date }}
                                    </p>
                                </div>

                                <div class="d-flex justify-content-between mb-5">
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-file-pdf"></i>
                                        Fatura:
                                        @if (isset($item->order->receipt_url))
                                            <a target="_blank"
                                                href="{{ route('orders.fatura', ['receipt_url' => $item->order->receipt_url]) }}"
                                                style="text-decoration: none;">
                                                {{ $item->order->receipt_url }}</a>
                                        @else
                                            Sem fatura
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="card-footer border-0 px-4 py-5 bg-dark text-light">
                                <h5 class="d-flex align-items-center justify-content-end text-white text-uppercase mb-0">
                                    Total:
                                    <span
                                        class="h2 mb-0 ms-2">{{ number_format($order->total_price, 2, ',', '.') . '€' }}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
