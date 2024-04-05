<div>
    <select class="form-select @error('code') is-invalid @enderror" name="code" id="input-color">
        @foreach ($colors as $color)
            <option value="{{ $color->code }}"
                {{ old('code', $orderItem->color_code ?? 'fafafa') == $color->code ? 'selected' : '' }}>
                {{ $color->name }}
            </option>
        @endforeach
    </select>
    @error('code')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mt-2">
    <select class="form-select @error('size') is-invalid @enderror" name="size" id="inputSize">
        <option value="XS" {{ old('size', $orderItem->size ?? 'size') === 'XS' ? 'selected' : '' }}>XS
        </option>
        <option value="S" {{ old('size', $orderItem->size ?? 'size') === 'S' ? 'selected' : '' }}>S
        </option>
        <option value="M" {{ old('size', $orderItem->size ?? 'size') === 'M' ? 'selected' : '' }}>M
        </option>
        <option value="L" {{ old('size', $orderItem->size ?? 'size') === 'L' ? 'selected' : '' }}>L
        </option>
        <option value="XL" {{ old('size', $orderItem->size ?? 'size') === 'XL' ? 'selected' : '' }}>XL
        </option>
    </select>
    @error('size')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
<div class="d-flex mt-2">
    <input class="form-control text-center me-3 @error('qty') is-invalid @enderror" name="qty" id="inputQty"
        type="number" min="1" max="100" style="max-width: 3rem"
        value="{{ old('qty', $orderItem->qty ?? 1) }}" />
    @error('qty')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
<br>
