<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserManagementDeleteUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_user_with_related_financial_records(): void
    {
        $admin = User::factory()->create(['role' => 4]);
        $user = User::factory()->create(['role' => 1]);

        DB::table('cuentacontable')->insert([
            'id' => 101,
            'descripcion' => 'Rent',
            'userId' => $user->id,
            'esProyeccion' => false,
        ]);

        DB::table('planificacion_financiera')->insert([
            'id' => 201,
            'descripcion' => 'Monthly plan',
            'nombre_del_plan' => 'June',
            'usuario_cedula' => $user->id,
            'valor_proyectado' => 700,
            'fecha_proyectada' => '2026-06-01 00:00:00',
            'proyecto_personal' => true,
        ]);

        DB::table('egreso')->insert([
            'id' => 301,
            'valor' => 650,
            'descripcion' => 'Rent payment',
            'estado' => 'paid',
            'fecha' => '2026-06-10 00:00:00',
            'planificacion_financiera_id' => 201,
            'cuentacontable_id' => 101,
            'user_id' => $user->id,
        ]);

        DB::table('ingreso')->insert([
            'id' => 401,
            'valor' => 1200,
            'fuente' => 'Salary',
            'fecha' => '2026-06-05 00:00:00',
            'planificacion_financiera_id' => 201,
            'cuentacontable_id' => 101,
            'user_id' => $user->id,
        ]);

        DB::table('operations_planified')->insert([
            'id' => 501,
            'id_planification' => 201,
            'date_creation' => '2026-06-01',
            'description' => 'Projected rent',
            'account_id' => 101,
            'user_id' => $user->id,
            'isRepetitive' => false,
            'projected_unit_value' => 700,
            'amount' => 1,
            'total_projected_value' => 700,
        ]);

        DB::table('sessions')->insert([
            'id' => 'user-session',
            'user_id' => $user->id,
            'payload' => 'payload',
            'last_activity' => 1,
        ]);

        DB::table('personal_access_tokens')->insert([
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
            'name' => 'test-token',
            'token' => str_repeat('a', 64),
            'abilities' => '["*"]',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)->delete(route('user-management.users.destroy', $user));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success', 'User deleted successfully.');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('egreso', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('ingreso', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('operations_planified', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('planificacion_financiera', ['usuario_cedula' => $user->id]);
        $this->assertDatabaseMissing('cuentacontable', ['userId' => $user->id]);
        $this->assertDatabaseMissing('sessions', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('personal_access_tokens', ['tokenable_id' => $user->id]);
    }

    public function test_admin_cannot_delete_own_user_account(): void
    {
        $admin = User::factory()->create(['role' => 4]);

        $response = $this->actingAs($admin)->delete(route('user-management.users.destroy', $admin));

        $response->assertSessionHasErrors('user');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}
