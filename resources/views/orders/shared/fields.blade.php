@php
    $disabledStr = $readonlyData ?? false ? 'disabled' : '';
    $user = $userType;
@endphp

<select class="form-select-sm form-control @error('payment_ref') is-invalid @enderror" name="status" {{ $disabledStr }}>
    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>
        Estado Pago
    </option>


    <option value="closed" {{ $order->status == 'closed' ? 'selected' : '' }}>
        Estado Fechado
    </option>

    @if ($readonlyData)
        <option value="pending" disabled {{ $order->status == 'pending' ? 'selected' : '' }}>
            Estado pendente
        </option>
    @endif

    @if($user == 'A')
        <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>
            Estado Cancelado
        </option>
    @endif

</select>

@error('status')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror
