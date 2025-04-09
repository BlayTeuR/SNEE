<?php

namespace Database\Seeders;

use App\Models\Depannage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepannageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Depannage::factory(10)->create();
    }
}
