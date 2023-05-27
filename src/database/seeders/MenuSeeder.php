<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::create([
            'name' => 'Teh Obeng',
            'category_id' => 1,
            'desc' => '',
            'price' => '5000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Teh O',
            'category_id' => 1,
            'desc' => '',
            'price' => '3000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Milo Panas',
            'category_id' => 1,
            'desc' => '',
            'price' => '10000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Milo Dingin',
            'category_id' => 1,
            'desc' => '',
            'price' => '6000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Cappucino Panas',
            'category_id' => 1,
            'desc' => '',
            'price' => '7000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Cappucino Dingin',
            'category_id' => 1,
            'desc' => '',
            'price' => '12000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Mie Kuah',
            'category_id' => 2,
            'desc' => '',
            'price' => '13000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Mie Goreng',
            'category_id' => 2,
            'desc' => '',
            'price' => '15000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Nasi Goreng',
            'category_id' => 2,
            'desc' => '',
            'price' => '15000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Nasi Goreng Seafood',
            'category_id' => 2,
            'desc' => '',
            'price' => '18000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Kwetiau Goreng',
            'category_id' => 2,
            'desc' => '',
            'price' => '17000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Kwetiau Basah',
            'category_id' => 2,
            'desc' => '',
            'price' => '17000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Mie Tarempa Kering',
            'category_id' => 2,
            'desc' => '',
            'price' => '17000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Mie Tarempa Basah',
            'category_id' => 2,
            'desc' => '',
            'price' => '17000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Roti Jhon',
            'category_id' => 3,
            'desc' => '',
            'price' => '15000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Burger Ayam',
            'category_id' => 3,
            'desc' => '',
            'price' => '14000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Burger Daging',
            'category_id' => 3,
            'desc' => '',
            'price' => '17000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Tahu Gejrot',
            'category_id' => 3,
            'desc' => '',
            'price' => '10000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Sotaang',
            'category_id' => 3,
            'desc' => '',
            'price' => '13000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Roti bakar ala resto',
            'category_id' => 3,
            'desc' => '',
            'price' => '10000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Keju Mozarella',
            'category_id' => 4,
            'desc' => '',
            'price' => '8000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Sambel Matah',
            'category_id' => 4,
            'desc' => '',
            'price' => '5000',
            'status' => true
        ]);

        Menu::create([
            'name' => 'Es Batu',
            'category_id' => 4,
            'desc' => '',
            'price' => '3000',
            'status' => true
        ]);
    }
}
