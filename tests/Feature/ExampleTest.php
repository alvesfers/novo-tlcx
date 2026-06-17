<?php

test('the application returns a successful response', function () {
    $response = $this->get('/');

    // Deve redirecionar para login se não autenticado
    $response->assertRedirect('/signin');
});

test('authenticated user can access home', function () {
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200);
});
