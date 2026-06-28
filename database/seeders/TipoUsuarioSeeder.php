<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_usuario')->upsert([
            [
                'id' => 1,
                'nombre' => 'Individual Role',
            ],
            [
                'id' => 2,
                'nombre' => 'Family Role',
            ],
            [
                'id' => 3,
                'nombre' => 'Business Role',
            ],
            [
                'id' => 4,
                'nombre' => 'Administrator Role',
            ],
        ], ['id'], ['nombre']);

        if (DB::getDriverName() === 'pgsql') {
            DB::statement(
                "select setval(pg_get_serial_sequence('tipo_usuario', 'id'), coalesce((select max(id) from tipo_usuario), 1), true)"
            );
        }
    }
}
