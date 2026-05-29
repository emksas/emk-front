<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('planificacion_financiera', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('descripcion', 45)->nullable();
            $table->string('nombre_del_plan', 45)->nullable();

            $table->foreignId('usuario_cedula')
                ->constrained('users');

            $table->double('valor_proyectado')->nullable();
            $table->date('fecha_proyectada')->nullable();
            $table->boolean('proyecto_personal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planificacion_financiera');
    }
};
