<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'nama' => 'Admin',
                'telp' => 'admin',
                'password' => bcrypt('admin'),
                'role' => 'admin',
                'is_verif' => true,
                'is_block' => false
            ],
            [
                'nama' => 'Syaiful',
                'telp' => '81234567890',
                'password' => bcrypt('syaiful'),
                'role' => 'user',
                'is_verif' => true,
                'is_block' => false
            ],
            [
                'nama' => 'Kirom',
                'telp' => '82345678901',
                'password' => bcrypt('kirom'),
                'role' => 'user',
                'is_verif' => true,
                'is_block' => false
            ],
        ];

        User::insert($users);
    }
}
