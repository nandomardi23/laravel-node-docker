<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $table->string('name');
        // $table->string('email')->unique();
        // $table->timestamp('email_verified_at')->nullable();
        // $table->string('password');
        // $table->string('photo');
        User::create([
            'name' => 'Admin 1',
            'email' => "admin1@gmail.com",
            'password' => bcrypt('12345678'),
            'photo' => ''
        ]);


        User::create([
            'name' => 'Admin 2',
            'email' => "admin2@gmail.com",
            'password' => bcrypt('12345678'),
            'photo' => ''
        ]);

        User::create([
            'name' => 'Kasir 1',
            'email' => "kasir1@gmail.com",
            'password' => bcrypt('12345678'),
            'photo' => ''
        ]);

        User::create([
            'name' => 'Kasir 2',
            'email' => "kasir2@gmail.com",
            'password' => bcrypt('12345678'),
            'photo' => ''
        ]);
    }
}
