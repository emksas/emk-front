<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\services\AccountingAccountService;
use App\services\ExpensesService;
use App\services\FinancialPlanningService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class ExpensesServiceTest extends TestCase
{
    public function test_fetch_expenses_returns_response_data_key_when_present(): void
    {
        config(['services.node_expenses.base_url' => 'http://node.test']);
        Http::fake(['*' => Http::response(['data' => [['amount' => 100]]])]);

        $result = $this->service()->fetchExpenses(15, '/Finanzas/rappi');

        $this->assertSame([], $result);
        Http::assertSent(fn ($request) =>
            $request->method() === 'GET' &&
            $request->url() === 'http://node.test/expenses/15?folderPath=%2FFinanzas%2Frappi&numberElements=10'
        );
    }

    public function test_fetch_expenses_returns_empty_array_when_remote_request_fails(): void
    {
        config(['services.node_expenses.base_url' => 'http://node.test']);
        Http::fake(['*' => Http::response([], 500)]);

        $result = $this->service()->fetchExpenses(15, '/Finanzas/rappi');

        $this->assertSame([], $result);
    }

    public function test_get_url_auth_microsoft_uses_authenticated_user_id(): void
    {
        config(['services.node_expenses_external.base_url' => 'http://node.test']);
        config(['app.url' => 'http://localhost']);
        $user = new User();
        $user->id = 42;
        Auth::shouldReceive('id')->once()->andReturn($user->id);

        $result = $this->service()->getUrlAuthMicrosoft();

        $this->assertSame(
            'http://node.test/auth/login/42?returnTo=http%3A%2F%2Flocalhost',
            $result
        );
    }

    private function service(): ExpensesService
    {
        return new ExpensesService(
            Mockery::mock(FinancialPlanningService::class),
            Mockery::mock(AccountingAccountService::class),
        );
    }
}
