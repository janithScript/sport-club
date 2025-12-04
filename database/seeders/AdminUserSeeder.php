<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@ousl.com'], // check by email

            [ // if NOT found, create using these values
                'name'      => 'Admin',
                'password'  => Hash::make('12345678'),
                'phone'     => '0770000000',
                'is_admin'  => 1,
            ]
        );
    }
}
