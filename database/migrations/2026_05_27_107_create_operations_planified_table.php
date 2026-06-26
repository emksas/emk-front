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
        Schema::create('operations_planified', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_planification')->nullable();
            $table->date('date_creation');
            $table->date('date_to_pay')->nullable();
            $table->string('description')->nullable();
            $table->integer('account_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->boolean('isRepetitive');
            $table->double('projected_unit_value')->nullable();
            $table->integer('amount')->nullable();
            $table->double('total_projected_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations_planified');
    }
};
