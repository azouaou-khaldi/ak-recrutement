<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'azouaoukhaldi07@gmail.com'],
            [
                'name'     => 'Azouaou Khaldi',
                'password' => Hash::make('TonMotDePasseIci'),
                'role'     => 'admin',
            ]
        );
    }
}
