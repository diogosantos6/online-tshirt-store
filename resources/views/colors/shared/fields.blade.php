@php
    $disabledStr = $readonlyData ?? false ? 'disabled' : '';
    $disabledColorCode = $createMode ?? false ? '' : 'disabled';
@endphp
<div class="card-header">
    <h5 class="card-title">Código</h5>
</div>
<div class="card-body">
    <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" id="inputCode"
        value="{{ old('code', $color->code) }}" {{ $disabledStr }} {{ $disabledColorCode }}>
    @error('code')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="card-header">
    <h5 class="card-title">Nome</h5>
</div>
<div class="card-body">
    <textarea class="form-control @error('name') is-invalid @enderror" name="name" id="inputName" rows="2"
        {{ $disabledStr }}>{{ old('name', $color->name) }}</textarea>
    @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
<div class="card-header">
    <h5 class="card-title">Cor</h5>
</div>
<div class="card-body">

    @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
