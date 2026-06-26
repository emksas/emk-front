<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanificacionFinancieraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('planificacion_financiera')->insert([
            [
                'id' => 1,
                'descripcion' => 'Default financial planning',
                'nombre_del_plan' => 'Starter Plan',
                'usuario_cedula' => 1032459533,
                'valor_proyectado' => 1000000,
                'fecha_proyectada' => now(),
                'proyecto_personal' => true,
            ],
        ]);
    }
}
