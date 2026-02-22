<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'nama_lengkap' => 'Admin Mas Reno',
                'role' => 'admin',
            ],
            [
                'username' => 'kasir1',
                'password' => Hash::make('kasir123'),
                'nama_lengkap' => 'Siti Kasir',
                'role' => 'kasir',
            ],
            [
                'username' => 'kasir2',
                'password' => Hash::make('kasir123'),
                'nama_lengkap' => 'Budi Kasir',
                'role' => 'kasir',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['username' => $user['username']], $user);
        }
    }
}
