<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create([
        'email' => 'pegawai@greenplanet.co.id',
    ]);

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'pegawai.baru@greenplanet.co.id',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('pegawai.baru@greenplanet.co.id', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('profile rejects email outside the company domain', function () {
    $user = User::factory()->create([
        'email' => 'pegawai@greenplanet.co.id',
    ]);

    $this->actingAs($user)
        ->patch('/profile', [
            'name' => $user->name,
            'email' => 'pegawai@gmail.com',
        ])
        ->assertSessionHasErrors('email');

    expect($user->fresh()->email)->toBe('pegawai@greenplanet.co.id');
});

test('profile rejects uppercase email addresses', function () {
    $user = User::factory()->create([
        'email' => 'pegawai@greenplanet.co.id',
    ]);

    $this->actingAs($user)
        ->patch('/profile', [
            'name' => $user->name,
            'email' => 'Pegawai@greenplanet.co.id',
        ])
        ->assertSessionHasErrors('email');

    expect($user->fresh()->email)->toBe('pegawai@greenplanet.co.id');
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create([
        'email' => 'pegawai@greenplanet.co.id',
    ]);

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});
