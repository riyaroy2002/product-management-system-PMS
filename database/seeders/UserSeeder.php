<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'        => 'Admin',
            'email'       => 'pms@yopmail.com',
            'password'    => 'Password123#',
            'is_verified' => 1,
            'is_block'    => 0,
            'role'     => 'admin'
        ]);
    }
}
