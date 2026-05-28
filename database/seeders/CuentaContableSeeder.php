<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CuentaContableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cuentacontable')->insert([
            [
                'descripcion' => 'Basic Services',
                'userId' => 1032459533,
            ],
            [
                'descripcion' => 'Extra Expenses',
                'userId' => 1032459533,
            ],
            [
                'descripcion' => 'Entertainment',
                'userId' => 1032459533,
            ],
        ]);
    }
}
