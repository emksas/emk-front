<?php

namespace Tests\Unit\Services;

use App\services\AccountingAccountIncomesService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\VarDumper\VarDumper;
use Tests\TestCase;

class AccountingAccountIncomesServiceTest extends TestCase
{
    public function test_get_all_requests_accounts_for_authenticated_user(): void
    {
        config(['services.spring_financial.base_url' => 'http://spring.test']);
        Auth::shouldReceive('id')->once()->andReturn(11);
        Http::fake([
            'http://spring.test/accounting-account/user/11' => Http::response([
                ['id' => 1, 'description' => 'Income account'],
            ]),
        ]);

        $previousDumper = VarDumper::setHandler(fn () => null);

        try {
            $result = (new AccountingAccountIncomesService())->getAll();
        } finally {
            VarDumper::setHandler($previousDumper);
        }

        $this->assertSame([['id' => 1, 'description' => 'Income account']], $result);
        Http::assertSent(fn ($request) => $request->url() === 'http://spring.test/accounting-account/user/11'
            && $request->method() === 'GET');
    }

    public function test_create_posts_account_payload_to_spring_service(): void
    {
        config(['services.spring_financial.base_url' => 'http://spring.test']);
        $payload = ['description' => 'New account', 'userId' => 11];
        Http::fake([
            'http://spring.test/accounting-account/' => Http::response(['id' => 4], 201),
        ]);

        $response = (new AccountingAccountIncomesService())->create($payload);

        $this->assertTrue($response->created());
        Http::assertSent(fn ($request) => $request->url() === 'http://spring.test/accounting-account/'
            && $request->method() === 'POST'
            && $request->data() === $payload);
    }
}
