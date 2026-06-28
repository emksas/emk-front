<?php

namespace Tests\Unit\Services;

use App\services\FinancialPlanningService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FinancialPlanningServiceTest extends TestCase
{
    public function test_get_by_user_id_returns_json_when_service_responds_successfully(): void
    {
        config(['services.spring_financial.base_url' => 'http://spring.test']);
        Http::fake([
            'http://spring.test/api/financial-planning/user/7' => Http::response([
                ['planId' => 1, 'description' => 'Savings'],
            ]),
        ]);

        $result = (new FinancialPlanningService())->getByUserId('7');

        $this->assertSame([['planId' => 1, 'description' => 'Savings']], $result);
    }

    public function test_get_by_user_id_returns_error_payload_when_service_fails(): void
    {
        config(['services.spring_financial.base_url' => 'http://spring.test']);
        Http::fake([
            'http://spring.test/api/financial-planning/user/7' => Http::response([], 503),
        ]);

        $result = (new FinancialPlanningService())->getByUserId('7');

        $this->assertSame([], $result);
    }

    public function test_create_maps_form_payload_and_posts_to_spring_service(): void
    {
        config(['services.spring_financial.base_url' => 'http://spring.test']);
        $payload = [
            'descripcion' => 'New plan',
            'valor' => '1000.00',
            'fecha' => '2026-06-27',
        ];
        Http::fake([
            'http://spring.test/api/financial-planning' => Http::response(['planId' => 9], 201),
        ]);

        $response = (new FinancialPlanningService())->create($payload, 7);

        $this->assertTrue($response->created());
        Http::assertSent(fn ($request) => $request->url() === 'http://spring.test/api/financial-planning'
            && $request->method() === 'POST'
            && $request->data() === [
                'userId' => 7,
                'planName' => 'New plan',
                'description' => 'New plan',
                'projectedValue' => '1000.00',
                'projectedDate' => '2026-06-27T00:00:00',
                'personalProject' => true,
            ]);
    }

    public function test_create_returns_failed_response_when_service_fails(): void
    {
        config(['services.spring_financial.base_url' => 'http://spring.test']);
        Http::fake([
            'http://spring.test/api/financial-planning' => Http::response([], 422),
        ]);

        $response = (new FinancialPlanningService())->create([
            'descripcion' => 'Invalid',
            'valor' => '0',
            'fecha' => '2026-06-27',
        ], 7);

        $this->assertTrue($response->failed());
        $this->assertSame(422, $response->status());
    }

    public function test_get_accounting_accounts_returns_json_from_spring_service(): void
    {
        config(['services.spring_financial.base_url' => 'http://spring.test/api']);
        Http::fake([
            'http://spring.test/api/accounting-account/user/7' => Http::response([
                ['id' => 4, 'description' => 'Rent'],
            ]),
        ]);

        $result = (new FinancialPlanningService())->getAccountingAccounts(7);

        $this->assertSame([['id' => 4, 'description' => 'Rent']], $result);
    }

    public function test_get_accounting_accounts_returns_empty_array_when_service_fails(): void
    {
        config(['services.spring_financial.base_url' => 'http://spring.test']);
        Http::fake([
            'http://spring.test/api/accounting-account/user/7' => Http::response([], 500),
        ]);

        $result = (new FinancialPlanningService())->getAccountingAccounts(7);

        $this->assertSame([], $result);
    }
}
