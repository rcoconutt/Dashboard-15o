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
            'name' => 'Ricardo',
            'last_name' => 'Osorio',
            'email' => 'ricardo@coconutt.io',
            'password' => \Illuminate\Support\Facades\Hash::make('coconutt.io'),
            'phone' => '55555555',
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

        \App\User::create([
            'name' => 'Alex',
            'last_name' => 'Santana',
            'email' => 'alex@1puntocinco.com',
            'password' => \Illuminate\Support\Facades\Hash::make('1puntocinco.com'),
            'phone' => '5555000000',
            'rol' => 0,
            'brand_id' => 0
        ]);
    }
}
