<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('egreso', 'user_id')) {
            Schema::table('egreso', function (Blueprint $table) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('cuentacontable_id')
                    ->constrained('users');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('egreso', 'user_id')) {
            Schema::table('egreso', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }
    }
};
