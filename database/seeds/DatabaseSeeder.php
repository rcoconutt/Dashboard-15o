<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'Daniel',
            'last_name' => 'Melendez',
            'email' => 'dan@1puntocinco.com',
            'password' => \Illuminate\Support\Facades\Hash::make('1puntocinco.com'),
            'phone' => '5555023936',
            'rol' => 0,
            'brand_id' => 0
        ]);

        \App\User::create([
            'name' => 'Daniel',
            'last_name' => 'Melendez',
            'email' => 'dan@1puntocinco.com',
            'password' => \Illuminate\Support\Facades\Hash::make('1puntocinco.com'),
            'phone' => '5555023936',
            'rol' => 0,
            'brand_id' => 0
        ]);
    }
}
