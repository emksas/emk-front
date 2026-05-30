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
        Schema::create('cuentacontable', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('descripcion', 45)->nullable();

            $table->foreignId('userId')
                ->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentacontable');
    }
};
