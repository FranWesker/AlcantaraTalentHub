<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    // Desactivamos el manejo de excepciones temporalmente para ver errores reales en la consola si falla
    $this->withoutExceptionHandling();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'student', // Cambiado a 'student' para coincidir con las políticas del sistema
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'role' => 'estudiante' // El controlador lo mapea automáticamente a español
    ]);
});
