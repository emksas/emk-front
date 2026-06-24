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
        Schema::create('ingreso', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->double('valor')->nullable();
            $table->string('fuente', 45)->nullable();
            $table->string('categoria', 45)->nullable();
            $table->string('metodo_de_pago', 45)->nullable();
            $table->timestamp('fecha')->nullable();
            $table->string('referencia', 45)->nullable();

            $table->foreignId('planificacion_financiera_id')
                ->constrained('planificacion_financiera');

            $table->foreignId('cuentacontable_id')
                ->constrained('cuentacontable');
            
            $table->foreignId('user_id')
                ->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingreso');
    }
};
