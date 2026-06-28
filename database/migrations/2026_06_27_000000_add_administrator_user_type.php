<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tipo_usuario')->updateOrInsert(
            ['id' => 4],
            [
                'nombre' => 'Administrator Role',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('tipo_usuario')->where('id', 4)->delete();
    }
};
