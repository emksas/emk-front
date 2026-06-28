<?php

namespace Tests\Unit\Services;

use App\services\IncomesService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IncomesServiceTest extends TestCase
{
    public function test_get_incomes_returns_normalized_success_payload(): void
    {
        config(['services.python_incomes.base_url' => 'http://django.test/api']);
        Http::fake([
            'http://django.test/api/incomes/*' => Http::response([
                ['id' => 1, 'value' => '1500.00'],
            ]),
        ]);

        $result = (new IncomesService())->getIncomes(5);

        $this->assertSame(200, $result['status']);
        $this->assertSame([['id' => 1, 'value' => '1500.00']], $result['incomes']);
    }

    public function test_get_incomes_returns_error_when_response_fails(): void
    {
        config(['services.python_incomes.base_url' => 'http://django.test/api']);
        Http::fake([
            'http://django.test/api/incomes/*' => Http::response([], 500),
        ]);

        $result = (new IncomesService())->getIncomes(5);

        $this->assertSame([], $result['incomes']);
        $this->assertSame('Error fetching data from income service', $result['error']);
        $this->assertSame(500, $result['status']);
    }

    public function test_get_incomes_returns_error_when_response_is_empty(): void
    {
        config(['services.python_incomes.base_url' => 'http://django.test/api']);
        Http::fake([
            'http://django.test/api/incomes/*' => Http::response([]),
        ]);

        $result = (new IncomesService())->getIncomes(5);

        $this->assertSame([], $result['incomes']);
        $this->assertSame('Invalid response format', $result['error']);
        $this->assertSame(500, $result['status']);
    }

    public function test_create_maps_form_payload_to_python_income_payload(): void
    {
        config(['services.python_incomes.base_url' => 'http://django.test/api']);
        Http::fake(['http://django.test/api/incomes/' => Http::response(['id' => 3], 201)]);

        (new IncomesService())->create([
            'valor' => '2000.50',
            'fuente' => 'Salary',
            'cuentacontable_id' => 12,
            'fecha' => '2026-06-26 10:45:00',
        ], '8');

        Http::assertSent(fn ($request) => $request->url() === 'http://django.test/api/incomes/'
            && $request->method() === 'POST'
            && $request->data() === [
                'value' => '2000.50',
                'source' => 'Salary',
                'accounting_account_id' => 12,
                'date' => '2026-06-26',
                'user_id' => 8,
                'financial_planning_id' => 1,
            ]);
    }

    public function test_update_income_maps_payload_to_existing_income_user_and_id(): void
    {
        config(['services.python_incomes.base_url' => 'http://django.test/api']);
        Http::fake(['*' => Http::response(['id' => 4])]);

        (new IncomesService())->updateIncome(
            ['id' => 4, 'user_id' => '9'],
            [
                'valor' => '700.00',
                'fuente' => 'Bonus',
                'cuentacontable_id' => 6,
                'fecha' => '2026-06-01',
            ],
        );

        Http::assertSent(fn ($request) => $request->url() === 'http://django.test/api/incomes/4/'
            && $request->method() === 'PUT'
            && $request->data()['user_id'] === 9
            && $request->data()['date'] === '2026-06-01');
    }

    public function test_delete_income_calls_python_delete_by_user_account_endpoint(): void
    {
        config(['services.python_incomes.base_url' => 'http://django.test/api']);
        Http::fake(['*' => Http::response([], 204)]);

        (new IncomesService())->deleteIncome(10, 8);

        Http::assertSent(fn ($request) => str_starts_with($request->url(), 'http://django.test/api/incomes/delete-by-user-account/')
            && str_contains($request->url(), 'user_id=10')
            && str_contains($request->url(), 'account_id=8')
            && $request->method() === 'DELETE');
    }
}
