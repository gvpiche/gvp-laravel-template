<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's default local users.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'password' => 'test1234',
            ],
        );

        $admin->assignRole($adminRole);

        User::updateOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'Regular User',
                'password' => 'test1234',
            ],
        );
    }
}
