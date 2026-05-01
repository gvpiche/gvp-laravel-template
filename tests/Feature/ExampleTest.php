<?php

use App\Models\User;

it('redirects guests from home to login', function () {
    $this->get('/')
        ->assertRedirect(route('login'));
});

it('redirects authenticated users from home to dashboard', function () {
    $this->actingAs(new User([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]))
        ->get('/')
        ->assertRedirect(route('dashboard'));
});
