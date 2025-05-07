<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => "admin",
            'email' => 'admin@example.com',
            'password' => bcrypt('motdepasseadmin'),
            'role' => Role::ADMIN,
        ]);

        User::factory()->create([
            'name' => "Jean",
            'email' => 'jean@example.com',
            'password' => bcrypt('motdepassetechnicien'),
            'role' => Role::TECHNICIEN,
        ]);

        User::factory()->create([
            'name' => "Michel",
            'email' => 'michel@example.com',
            'password' => bcrypt('motdepassetechnicien'),
            'role' => Role::TECHNICIEN,
        ]);

        User::factory()->create([
            'name' => "Fred",
            'email' => 'fred@example.com',
            'password' => bcrypt('motdepassetechnicien'),
            'role' => Role::TECHNICIEN,
        ]);
    }
}
