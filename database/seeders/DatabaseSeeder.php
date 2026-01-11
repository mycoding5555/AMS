<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            FloorSeeder::class,
            ApartmentSeeder::class,
        ]);

        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => bcrypt('12345678'),
                'email_verified_at' => now(),
                'status' => 'active'
            ]
        );
        
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Create supervisor user
        $supervisor = User::updateOrCreate(
            ['email' => 'sup@gmail.com'],
            [
                'name' => 'supervisor',
                'password' => bcrypt('12345678'),
                'email_verified_at' => now(),
                'status' => 'active'
            ]
        );
        
        if (!$supervisor->hasRole('supervisor')) {
            $supervisor->assignRole('supervisor');
        }

        // Create regular user
        $user = User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'user',
                'password' => bcrypt('12345678'),
                'email_verified_at' => now(),
                'status' => 'active'
            ]
        );
        
        if (!$user->hasRole('tenant')) {
            $user->assignRole('tenant');
        }
    }
}
