<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::create([
            'name' => 'Daniel Farfan',
            'phone' => '981234567',
            'email' => 'dfarfan349@gmail.com',
            'profile' => 'ADMIN',
            'status' => 'ACTIVE',
            'password' => bcrypt('123')
            
        ]);
        User::create([
            'name' => 'Jorge Farfan',
            'phone' => '981234537',
            'email' => 'jorge@gmail.com',
            'profile' => 'EMPLOYEE',
            'status' => 'ACTIVE',
            'password' => bcrypt('123')
            
        ]);
    }
}
