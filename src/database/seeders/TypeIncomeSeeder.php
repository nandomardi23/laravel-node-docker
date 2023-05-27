<?php

namespace Database\Seeders;

use App\Models\TypeIncome;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypeIncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeIncome::create([
            'name' => 'Modal',
        ]);
        TypeIncome::create([
            'name' => 'Sumbangan',
        ]);
        TypeIncome::create([
            'name' => 'Pendapatan Organisasi',
        ]);
        TypeIncome::create([
            'name' => 'Hasil galang dana rapat',
        ]);
    }
}
