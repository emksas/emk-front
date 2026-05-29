<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('egreso', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->double('valor');
            $table->string('descripcion', 45);
            $table->string('estado', 45);
            $table->timestamp('fecha');

            $table->unsignedBigInteger('planificacion_financiera_id');
            $table->unsignedBigInteger('cuentacontable_id');

            $table->foreign('planificacion_financiera_id', 'fk_egreso_planificacion')
                ->references('id')
                ->on('planificacion_financiera');

            $table->foreign('cuentacontable_id', 'fk_egreso_cuentacontable')
                ->references('id')
                ->on('cuentacontable');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egreso');
    }
};
