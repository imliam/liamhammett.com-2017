<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Liam Hammett',
            'email' => 'liam@liamhammett.com',
            'password' => bcrypt('secret'),
        ]);
    }
}
