<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categories::create([
            'name' => "Minuman",
        ]);

        Categories::create([
            'name' => "Makanan",
        ]);

        Categories::create([
            'name' => "Cemilan",
        ]);

        Categories::create([
            'name' => "Lain-lain",
        ]);
    }
}
