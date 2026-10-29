<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/\A[a-z0-9._%+-]+@greenplanet\.co\.id\z/',
                'unique:users,email,' . $user->id,
            ],
        ]);

        $emailChanged = $user->email !== $validated['email'];

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        // Simpan satu kali agar tidak memicu update berulang.
        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profile berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Password berhasil diubah.');
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();

        // Jangan izinkan penghapusan akun super admin terakhir.
        if (
            $user->isSuperAdmin()
            && !DB::table('users')
                ->where('role', 'super_admin')
                ->where('id', '!=', $user->id)
                ->exists()
        ) {
            return redirect()
                ->route('profile.edit')
                ->withErrors([
                    'password' => 'Akun super admin terakhir tidak dapat dihapus.',
                ]);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    
}