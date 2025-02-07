<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function PHPUnit\Framework\assertTrue;

test('registration screen can be rendered', function () {
    $response = get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    assertAuthenticated();

    $response->assertRedirect(route('dashboard', absolute: false));

    $user = User::whereEmail('test@example.com')->first();

    assertTrue($user->wallet !== null);
});
