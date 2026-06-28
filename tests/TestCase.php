<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (Schema::hasTable('tipo_usuario')) {
            DB::table('tipo_usuario')->insertOrIgnore([
                ['id' => 1, 'nombre' => 'Individual Role'],
                ['id' => 2, 'nombre' => 'Family Role'],
                ['id' => 3, 'nombre' => 'Business Role'],
                ['id' => 4, 'nombre' => 'Administrator Role'],
            ]);
        }
    }
}
