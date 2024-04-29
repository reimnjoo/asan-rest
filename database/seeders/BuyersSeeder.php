<?php

namespace Database\Seeders;

use App\Models\Buyers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BuyersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Buyers::factory()->count(25)->create();
    }
}
