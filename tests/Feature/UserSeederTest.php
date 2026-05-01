<?php

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('it seeds the default local users and admin role', function () {
    $this->seed(UserSeeder::class);

    $admin = User::where('email', 'gabriel_vpiche@hotmail.com')->first();
    $user = User::where('email', 'user@gvptools.com')->first();

    expect(Role::where('name', 'Admin')->exists())->toBeTrue()
        ->and($admin)->not->toBeNull()
        ->and($admin->hasRole('Admin'))->toBeTrue()
        ->and($admin->password)->not->toBe('test1234')
        ->and($user)->not->toBeNull()
        ->and($user->roles)->toHaveCount(0)
        ->and($user->password)->not->toBe('test1234');
});
