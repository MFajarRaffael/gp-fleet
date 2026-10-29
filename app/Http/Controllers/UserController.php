<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $isSuperAdmin = $request->user()->isSuperAdmin();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/\A[a-z0-9._%+-]+@greenplanet\.co\.id\z/',
                'unique:users,email',
            ],
            'password' => 'required|string|min:8|confirmed',
        ];

        if ($isSuperAdmin) {
            $rules['role'] = 'required|in:user,admin,super_admin';
        }

        $validated = $request->validate($rules);

        // Buat akun tanpa mengirim role melalui mass assignment.
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Tetapkan role secara eksplisit.
        $user->role = $isSuperAdmin
            ? $validated['role']
            : 'user';

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $this->authorizeUserManagement($user);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeUserManagement($user);

        $currentUser = $request->user();
        $isSuperAdmin = $currentUser->isSuperAdmin();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/\A[a-z0-9._%+-]+@greenplanet\.co\.id\z/',
                'unique:users,email,' . $user->id,
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ];

        if ($isSuperAdmin) {
            $rules['role'] = 'required|in:user,admin,super_admin';
        }

        $validated = $request->validate($rules);

        // Super admin tidak boleh menurunkan role akunnya sendiri.
        if (
            $isSuperAdmin &&
            $user->id === $currentUser->id &&
            $validated['role'] !== 'super_admin'
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'role' => 'Kamu tidak dapat menurunkan role akun sendiri.',
                ]);
        }

        // Lindungi super admin terakhir.
        if (
            $isSuperAdmin &&
            $user->isSuperAdmin() &&
            $validated['role'] !== 'super_admin'
        ) {
            $jumlahSuperAdmin = User::where('role', 'super_admin')->count();

            if ($jumlahSuperAdmin <= 1) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'role' => 'Role super admin terakhir tidak boleh diturunkan.',
                    ]);
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($isSuperAdmin) {
            $user->role = $validated['role'];
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun berhasil diupdate.');
    }


    public function destroy(User $user)
    {
        $currentUser = request()->user();

        if ($user->isSuperAdmin()) {
            abort(403, 'Akun super admin tidak dapat dihapus melalui menu ini.');
        }

        if ($user->id === $currentUser->id) {
            return back()->with(
                'error',
                'Akun yang sedang digunakan tidak dapat dihapus.'
            );
        }

        if (!$currentUser->isSuperAdmin() && $user->role === 'admin') {
            abort(403, 'Hanya super admin yang dapat menghapus akun admin.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun berhasil dihapus.');
    }

    private function authorizeUserManagement(User $user): void
    {
        $currentUser = request()->user();

        // Super admin boleh mengelola akun melalui menu ini.
        if ($currentUser->isSuperAdmin()) {
            return;
        }

        // Admin hanya boleh mengelola akun dengan role user.
        if ($user->role !== 'user') {
            abort(403, 'Admin hanya dapat mengelola akun pengguna biasa.');
        }

        // Admin tidak boleh mengedit akunnya sendiri melalui menu ini.
        if ($user->id === $currentUser->id) {
            abort(403, 'Admin tidak dapat mengubah akunnya sendiri melalui menu ini.');
        }
    }
}