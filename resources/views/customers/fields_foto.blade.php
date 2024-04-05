<img src="{{ $customer->user->fullPhotoUrl }}" alt="Avatar" class="rounded-circle img-thumbnail">
@if ($allowUpload)
    <div class="mb-3 pt-3">
        <input type="file" class="form-control @error('file_foto') is-invalid @enderror" name="file_foto"
            id="inputFileFoto">
        @error('file_foto')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
@endif
@if (($allowDelete ?? false) && $customer->user->photo_url)
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmationModal"
        data-action="{{ route('customers.foto.destroy', ['customer' => $customer]) }}"
        data-msgLine2="Quer realmente apagar a fotografia do cliente <strong>{{ $customer->user->name }}</strong>?">
        Apagar Foto
    </button>
@endif
