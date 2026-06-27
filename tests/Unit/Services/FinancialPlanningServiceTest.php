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
            'http://spring.test/financial-planning/user/7' => Http::response([
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
            'http://spring.test/financial-planning/user/7' => Http::response([], 503),
        ]);

        $result = (new FinancialPlanningService())->getByUserId('7');

        $this->assertSame([], $result['financialPlannings']);
        $this->assertSame('Error fetching data from financial planning service', $result['error']);
        $this->assertSame(503, $result['spring_status']);
    }

    public function test_create_posts_payload_and_returns_response_json(): void
    {
        config(['services.spring_financial.base_url' => 'http://spring.test']);
        $payload = ['description' => 'New plan'];
        Http::fake([
            'http://spring.test/financial-planning' => Http::response(['planId' => 9], 201),
        ]);

        $result = (new FinancialPlanningService())->create($payload);

        $this->assertSame(['planId' => 9], $result['response']);
        Http::assertSent(fn ($request) => $request->url() === 'http://spring.test/financial-planning'
            && $request->method() === 'POST'
            && $request->data() === $payload);
    }

    public function test_create_returns_error_reason_when_service_fails(): void
    {
        config(['services.spring_financial.base_url' => 'http://spring.test']);
        Http::fake([
            'http://spring.test/financial-planning' => Http::response([], 422),
        ]);

        $result = (new FinancialPlanningService())->create(['description' => 'Invalid']);

        $this->assertArrayHasKey('error', $result);
    }
}
