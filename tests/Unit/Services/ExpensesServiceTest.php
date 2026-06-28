<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\services\ExpensesService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExpensesServiceTest extends TestCase
{
    public function test_fetch_expenses_returns_response_data_key_when_present(): void
    {
        config(['services.node_expenses.base_url' => 'http://node.test']);
        Http::fake(['*' => Http::response(['data' => [['amount' => 100]]])]);

        $result = (new ExpensesService())->fetchExpenses(15);

        $this->assertSame([['amount' => 100]], $result);
        Http::assertSent(fn ($request) => $request->method() === 'GET');
    }

    public function test_fetch_expenses_returns_false_when_remote_request_fails(): void
    {
        config(['services.node_expenses.base_url' => 'http://node.test']);
        Http::fake(['*' => Http::response([], 500)]);

        $result = (new ExpensesService())->fetchExpenses(15);

        $this->assertFalse($result);
    }

    public function test_get_url_auth_microsoft_uses_authenticated_user_id(): void
    {
        config(['services.node_expenses.base_url' => 'http://node.test']);
        config(['app.url' => 'http://localhost']);
        $user = new User();
        $user->id = 42;
        Auth::shouldReceive('id')->once()->andReturn($user->id);

        $result = (new ExpensesService())->getUrlAuthMicrosoft();

        $this->assertSame(
            'http://node.test/auth/login/42?returnTo=http%3A%2F%2Flocalhost%2Fmicrosoft%2Fauth%2Fcallback',
            $result
        );
    }
}
