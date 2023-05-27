<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Table::create([
            'number' => 1,
            'desc' => 'Sedang dalam perbaikan'
        ]);

        Table::create([
            'number' => 2,
            'desc' => ''
        ]);

        Table::create([
            'number' => 3,
            'desc' => ''
        ]);

        Table::create([
            'number' => 4,
            'desc' => ''
        ]);

        Table::create([
            'number' => 5,
            'desc' => ''
        ]);

        Table::create([
            'number' => 6,
            'desc' => ''
        ]);

        Table::create([
            'number' => 7,
            'desc' => ''
        ]);

        Table::create([
            'number' => 8,
            'desc' => ''
        ]);

        Table::create([
            'number' => 9,
            'desc' => ''
        ]);

        Table::create([
            'number' => 10,
            'desc' => ''
        ]);

        Table::create([
            'number' => 11,
            'desc' => ''
        ]);

        Table::create([
            'number' => 12,
            'desc' => ''
        ]);

        Table::create([
            'number' => 13,
            'desc' => ''
        ]);

        Table::create([
            'number' => 14,
            'desc' => ''
        ]);

    }
}
