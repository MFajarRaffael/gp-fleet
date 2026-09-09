<form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('PUT')

    <div class="card-body">

        <div class="form-group">
            <label>Password Saat Ini</label>

            <input type="password" name="current_password" class="form-control" required>

            @error('current_password', 'updatePassword')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Password Baru</label>

            <input type="password" name="password" class="form-control" required>

            @error('password', 'updatePassword')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Konfirmasi Password Baru</label>

            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-warning">
            <i class="fas fa-key mr-1"></i>
            Ganti Password
        </button>
    </div>
</form>