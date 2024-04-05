@php
    $disabledStr = $readonlyData ?? false ? 'disabled' : '';
    if ((Auth::user()->user_type ?? '') == 'A') {
        $editPermission = 'disabled';
    }
    else {
        $editPermission = '';
    }
@endphp

<div class="mb-3 form-floating">
    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="inputNome"
        {{ $disabledStr }} {{ $editPermission }} value="{{ old('name', $customer->user->name)}}">
    <label for="inputNome" class="form-label">Nome</label>
    @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3 form-floating">
    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" id="inputEmail"
        {{ $disabledStr }} {{ $editPermission }} value="{{ old('email', $customer->user->email)}}">
    <label for="inputEmail" class="form-label">Email</label>
    @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

@if($customer->user->user_type == 'C')
<div class="mb-3 form-floating">
    <input type="text" class="form-control @error('nif') is-invalid @enderror" name="nif" id="inputNif"
        {{ $disabledStr }} {{ $editPermission }} value="{{ old('nif', $customer->nif) }}" >
    <label for="inputNif" class="form-label">Nif</label>
    @error('nif')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3 form-floating">
    <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="inputAddress"
        {{ $disabledStr }} {{ $editPermission }} value="{{ old('address', $customer->address) }}" >
    <label for="inputAddress" class="form-label">Morada</label>
    @error('address')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3 form-floating">
    <select class="form-control @error('default_payment_type') is-invalid @enderror" name="default_payment_type" id="selDef_pay_type" {{ $disabledStr }} {{ $editPermission }}>
        <option value="VISA"{{ old('default_payment_type', $customer->default_payment_type)=='VISA' ? 'selected' : '' }} >Visa</option>
        <option value="MC"{{ old('default_payment_type', $customer->default_payment_type)=='MC' ? 'selected' : '' }} >Mc</option>
        <option value="PAYPAL"{{ old('default_payment_type', $customer->default_payment_type)=='PAYPAL' ? 'selected' : '' }} >Paypal</option>
        <option value=""{{ old('default_payment_type', $customer->default_payment_type)=='' ? 'selected' : '' }} >Nenhum</option>
    </select>
    <label for="selDef_pay_type" class="form-label">Tipo de pagamento</label>
    @error('default_payment_type')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3 form-floating">
    <input type="text" class="form-control @error('default_payment_ref') is-invalid @enderror" name="default_payment_ref" id="inputDef_pay_ref"
        {{ $disabledStr }} {{ $editPermission }} value="{{ old('default_payment_ref', $customer->default_payment_ref) }}" >
    <label for="inputDef_pay_ref" class="form-label">Referência de pagamento</label>
    @error('default_payment_ref')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
@endif

@if ((Auth::user()->user_type ?? '') == 'A')

    <div class="mb-3">
        <div class="form-check form-switch" {{ $disabledStr }}>
            <input type="hidden" name="blocked" value="0">
            <input type="checkbox" class="form-check-input @error('blocked') is-invalid @enderror" name="blocked"
                id="inputBlocked" {{ $disabledStr }} {{ old('blocked', $customer->user->blocked=='1') ? 'checked' : '' }} value="1">
            <label for="inputBlocked" class="form-check-label">Bloqueado</label>
            @error('blocked')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

@endif
