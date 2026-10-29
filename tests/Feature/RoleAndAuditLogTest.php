<?php

use App\Models\AuditLog;
use App\Models\User;

it('membatasi akses user ke pengelolaan pengguna, PIC, dan riwayat aktivitas', function () {
    $user = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertForbidden();

    $this->get(route('pic.create'))->assertForbidden();

    $this->get(route('audit-logs.index'))->assertForbidden();
});

it('mengizinkan user mengakses halaman kendaraan dan dokumen', function () {
    $user = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);

    $this->actingAs($user)
        ->get(route('kendaraan.index'))
        ->assertOk();

    $this->get(route('dokumen.index'))->assertOk();
});

it('mencegah admin mengedit akun super admin', function () {
    $admin = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);
    $admin->role = 'admin';
    $admin->save();

    $superAdmin = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);
    $superAdmin->role = 'super_admin';
    $superAdmin->save();

    $this->actingAs($admin)
        ->get(route('users.edit', $superAdmin))
        ->assertForbidden();
});

it('mencegah admin membuat akun super admin melalui manipulasi request', function () {
    $admin = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);
    $admin->role = 'admin';
    $admin->save();

    $response = $this->actingAs($admin)->post(route('users.store'), [
        'name' => 'Pengguna Pengujian',
        'email' => 'uji-role@greenplanet.co.id',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'super_admin',
    ]);

    $response->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', [
        'email' => 'uji-role@greenplanet.co.id',
        'role' => 'user',
    ]);
});

it('mencegah super admin menurunkan role akunnya sendiri', function () {
    $superAdmin = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);
    $superAdmin->role = 'super_admin';
    $superAdmin->save();

    // Pastikan masih ada super admin lain.
    $anotherSuperAdmin = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);
    $anotherSuperAdmin->role = 'super_admin';
    $anotherSuperAdmin->save();

    $this->actingAs($superAdmin)
        ->put(route('users.update', $superAdmin), [
            'name' => $superAdmin->name,
            'email' => $superAdmin->email,
            'role' => 'admin',
        ])
        ->assertSessionHasErrors('role');

    expect($superAdmin->fresh()->role)->toBe('super_admin');
});

it('menolak pembuatan akun dengan email di luar domain perusahaan', function () {
    $superAdmin = User::factory()->create([
        'email' => 'superadmin@greenplanet.co.id',
        'role' => 'super_admin',
    ]);

    $this->actingAs($superAdmin)
        ->post(route('users.store'), [
            'name' => 'Pengguna Pengujian',
            'email' => 'pegawai@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ])
        ->assertSessionHasErrors('email');

    $this->assertDatabaseMissing('users', [
        'email' => 'pegawai@gmail.com',
    ]);
});

it('menolak pembuatan akun dengan email huruf besar', function () {
    $superAdmin = User::factory()->create([
        'email' => 'superadmin@greenplanet.co.id',
        'role' => 'super_admin',
    ]);

    $this->actingAs($superAdmin)
        ->post(route('users.store'), [
            'name' => 'Pengguna Pengujian',
            'email' => 'Pegawai@greenplanet.co.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ])
        ->assertSessionHasErrors('email');
});

it('menolak pengeditan akun dengan email di luar domain perusahaan', function () {
    $superAdmin = User::factory()->create([
        'email' => 'superadmin@greenplanet.co.id',
        'role' => 'super_admin',
    ]);

    $user = User::factory()->create([
        'email' => 'pegawai@greenplanet.co.id',
    ]);

    $this->actingAs($superAdmin)
        ->put(route('users.update', $user), [
            'name' => $user->name,
            'email' => 'pegawai@gmail.com',
            'role' => 'user',
        ])
        ->assertSessionHasErrors('email');

    expect($user->fresh()->email)->toBe('pegawai@greenplanet.co.id');
});

it('menolak pengeditan akun dengan email huruf besar', function () {
    $superAdmin = User::factory()->create([
        'email' => 'superadmin@greenplanet.co.id',
        'role' => 'super_admin',
    ]);

    $user = User::factory()->create([
        'email' => 'pegawai@greenplanet.co.id',
    ]);

    $this->actingAs($superAdmin)
        ->put(route('users.update', $user), [
            'name' => $user->name,
            'email' => 'Pegawai@greenplanet.co.id',
            'role' => 'user',
        ])
        ->assertSessionHasErrors('email');

    expect($user->fresh()->email)->toBe('pegawai@greenplanet.co.id');
});

it('tidak menyimpan password atau remember token dalam audit log pengguna', function () {
    $user = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);

    $log = AuditLog::query()
        ->where('auditable_type', User::class)
        ->where('auditable_id', $user->id)
        ->where('action', 'user_created')
        ->firstOrFail();

    expect($log->new_values)->not->toHaveKey('password');
    expect($log->new_values)->not->toHaveKey('remember_token');
});


it('mencegah admin menghapus akun admin lain', function () {
    $admin = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);
    $admin->role = 'admin';
    $admin->save();

    $anotherAdmin = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);
    $anotherAdmin->role = 'admin';
    $anotherAdmin->save();

    $this->actingAs($admin)
        ->delete(route('users.destroy', $anotherAdmin))
        ->assertForbidden();

    expect($anotherAdmin->fresh())->not->toBeNull();
});

it('mencegah admin menghapus akun super admin', function () {
    $admin = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);
    $admin->role = 'admin';
    $admin->save();

    $superAdmin = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);
    $superAdmin->role = 'super_admin';
    $superAdmin->save();

    $this->actingAs($admin)
        ->delete(route('users.destroy', $superAdmin))
        ->assertForbidden();

    expect($superAdmin->fresh())->not->toBeNull();
});

it('mencegah super admin terakhir menghapus akun sendiri dari profil', function () {
    $superAdmin = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);
    $superAdmin->role = 'super_admin';
    $superAdmin->save();

    $this->actingAs($superAdmin)
        ->delete(route('profile.destroy'), [
            'password' => 'password',
        ])
        ->assertRedirect(route('profile.edit'));

    expect($superAdmin->fresh())->not->toBeNull();
});

it('mencatat penghapusan akun dalam audit log', function () {
    $admin = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);
    $admin->role = 'super_admin';
    $admin->save();

    $user = User::factory()->create([
        'email' => fake()->unique()->userName() . '@greenplanet.co.id',
    ]);

    $this->actingAs($admin)
        ->delete(route('users.destroy', $user))
        ->assertRedirect(route('users.index'));

    expect(
        AuditLog::query()
            ->where('action', 'user_deleted')
            ->where('auditable_id', $user->id)
            ->exists()
    )->toBeTrue();
});


it('menolak akses halaman internal bagi pengguna yang belum login', function () {
    $halamanInternal = [
        '/dashboard',
        '/kendaraan',
        '/dokumen',
        '/pic',
        '/users',
        '/riwayat-aktivitas',
        '/notifikasi',
        '/search/global',
        '/push-subscriptions/status',
        '/hse',
    ];

    foreach ($halamanInternal as $halaman) {
        $this->get($halaman)
            ->assertRedirect(route('login'));
    }
});
