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

        User::factory()->create([
            'name' => "Benjamin",
            'email' => 'Benjamin@example.com',
            'password' => bcrypt('motdepassetechnicien'),
            'role' => Role::TECHNICIEN,
        ]);

        User::factory()->create([
            'name' => "pierre",
            'email' => 'pierre@example.com',
            'password' => bcrypt('motdepassetechnicien'),
            'role' => Role::TECHNICIEN,
        ]);

        User::factory()->create([
            'name' => "claude",
            'email' => 'claude@example.com',
            'password' => bcrypt('motdepassetechnicien'),
            'role' => Role::TECHNICIEN,
        ]);

        User::factory()->create([
            'name' => "aude",
            'email' => 'aude@example.com',
            'password' => bcrypt('motdepassetechnicien'),
            'role' => Role::TECHNICIEN,
        ]);

        User::factory()->create([
            'name' => "jules",
            'email' => 'jules@example.com',
            'password' => bcrypt('motdepassetechnicien'),
            'role' => Role::TECHNICIEN,
        ]);

        User::factory()->create([
            'name' => "bertrand",
            'email' => 'bertrand@example.com',
            'password' => bcrypt('motdepassetechnicien'),
            'role' => Role::TECHNICIEN,
        ]);

        User::factory()->create([
            'name' => "antoine",
            'email' => 'antoine@example.com',
            'password' => bcrypt('motdepassetechnicien'),
            'role' => Role::TECHNICIEN,
        ]);

        User::factory()->create([
            'name' => "bastien",
            'email' => 'bastien@example.com',
            'password' => bcrypt('motdepassetechnicien'),
            'role' => Role::TECHNICIEN,
        ]);
    }
}
