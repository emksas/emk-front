<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1032459533,
                'name' => 'Ramses',
                'email' => 'ramsessr@outlook.com',
                'email_verified_at' => now(),
                'password' => Hash::make('isis1998'),
                'current_team_id' => null,
                'profile_photo_path' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'role' => 4
            ],
        ]);
    }
}
