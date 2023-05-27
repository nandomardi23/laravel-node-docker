<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create([
            'name' => 'App Kasir',
            'phone' =>'08123456789',
            'email'=>'kedaikopi@gmail.com',
            'address'=>'jalan kaki no.34',
        ]);
    }
}
