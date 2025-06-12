<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')->insert([
            'name' => 'DEMAY Romain',
            'email' => 'demay@mail.com',
            'role' => 'technicien',
            'password' => Hash::make('romain'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'GAUDEL Adrien',
            'email' => 'gaudel@mail.com',
            'role' => 'technicien',
            'password' => Hash::make('adrien'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'GAUSSURON Raphaël',
            'email' => 'gaussuron@mail.com',
            'role' => 'technicien',
            'password' => Hash::make('raphael'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'GODART David',
            'email' => 'godart@mail.com',
            'role' => 'technicien',
            'password' => Hash::make('david'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'GOMES Anthony',
            'email' => 'gomes@mail.com',
            'role' => 'technicien',
            'password' => Hash::make('anthony'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'MARCHAL Gaëtan',
            'email' => 'marchal@mail.com',
            'role' => 'technicien',
            'password' => Hash::make('gaetan'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Ruiz Muigel',
            'email' => 'ruiz@mail.com',
            'role' => 'technicien',
            'password' => Hash::make('miguel'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', 'demay@mail.com')->delete();
        DB::table('users')->where('email', 'gaudel@mail.com')->delete();
        DB::table('users')->where('email', 'gaussuron@mail.com')->delete();
        DB::table('users')->where('email', 'godart@mail.com')->delete();
        DB::table('users')->where('email', 'gomes@mail.com')->delete();
        DB::table('users')->where('email', 'marchal@mail.com')->delete();
        DB::table('users')->where('email', 'ruiz@mail.com')->delete();
    }
};
