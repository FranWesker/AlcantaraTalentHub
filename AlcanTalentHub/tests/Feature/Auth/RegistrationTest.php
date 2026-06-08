<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'account_type' => 'student',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();

    // Verificamos que se tradujo correctamente al guardar en BD
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'role' => 'estudiante',
    ]);
});
