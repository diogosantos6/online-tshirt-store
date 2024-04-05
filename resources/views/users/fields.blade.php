@php
    $disabledStr = $readonlyData ?? false ? 'disabled' : '';
@endphp

<div class="mb-3 form-floating">
    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="inputNome"
        {{ $disabledStr }}  value="{{ old('name', $user->name) }}">
    <label for="inputNome" class="form-label">Nome</label>
    @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3 form-floating">
    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" id="inputEmail"
        {{ $disabledStr }}  value="{{ old('email', $user->email) }}">
    <label for="inputEmail" class="form-label">Email</label>
    @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

@if((Auth::user()->user_type ?? '') == 'A')
    <div class="mb-3">
        <div class="form-check form-switch" {{ $disabledStr }}>
            <input type="hidden" name="blocked" value="0">
            <input type="checkbox" class="form-check-input @error('blocked') is-invalid @enderror" name="blocked"
                id="inputBlocked" {{ $disabledStr }} {{ old('blocked', $user->blocked=='1') ? 'checked' : '' }} value="1">
            <label for="inputBlocked" class="form-check-label">Bloqueado</label>
            @error('blocked')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    @if(($user->user_type ?? '') != 'C')
        <div class="mb-3">
            <div class="form-check-select" {{ $disabledStr }}>
                <label for="inputOpcional" class="form-check-label @error('user_type') is-invalid @enderror">Tipo</label>
                <select class="form-check-select" name="user_type" id="inputOpcional" {{ $disabledStr }}>
                    <option {{ old('user_type', $user->user_type=='A') ? 'selected' : '' }} value="A">Administrator</option>
                    <option {{ old('user_type', $user->user_type=='E') ? 'selected' : '' }} value="E">Funcionário</option>
                </select>
                @error('user_type')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    @endif

@endif
