<form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PATCH')

    <div class="card-body">

        <div class="form-group">
            <label>
                <i class="fas fa-user mr-1"></i>
                Nama
            </label>

            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>

            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>
                <i class="fas fa-envelope mr-1"></i>
                Email
            </label>

            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>

            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i>
            Simpan Perubahan
        </button>
    </div>
</form>