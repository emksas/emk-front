<?php

namespace Tests\Unit\Services;

use App\services\DashboardServices;
use App\services\ExpensesService;
use App\services\FinancialPlanningService;
use App\services\IncomesService;
use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;

class DashboardServicesTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_dashboard_data_uses_expense_service_values(): void
    {
        $expensesService = Mockery::mock(ExpensesService::class);
        $expensesService->shouldReceive('getMonthlyExpenses')->once()->with(6, 2026, null)->andReturn([
            ['valor' => 100],
            ['valor' => 200],
        ]);
        $expensesService->shouldReceive('getSumOfExpensesByMonth')->once()->with(6, 2026, null)->andReturn(300);
        $expensesService->shouldReceive('getMonthlyExpensesByAccount')->once()->with(6, 2026, null)->andReturn([
            ['descripcion' => 'Food', 'total' => 300],
        ]);

        $result = (new DashboardServices($expensesService))->getDashboardData(2026, 6);

        $this->assertSame(300, $result['totalExpenses']);
        $this->assertSame(2, $result['numberOfExpenses']);
        $this->assertSame('June', $result['monthName']);
        $this->assertSame(6, $result['month']);
        $this->assertSame(2026, $result['year']);
    }

    public function test_get_dashboard_data_adds_personal_finance_summary(): void
    {
        $expensesService = Mockery::mock(ExpensesService::class);
        $expensesService->shouldReceive('getMonthlyExpenses')->once()->with(6, 2026, 7)->andReturn([]);
        $expensesService->shouldReceive('getSumOfExpensesByMonth')->once()->with(6, 2026, 7)->andReturn(650);
        $expensesService->shouldReceive('getMonthlyExpensesByAccount')->once()->with(6, 2026, 7)->andReturn([]);

        $incomesService = Mockery::mock(IncomesService::class);
        $incomesService->shouldReceive('getIncomes')->once()->with(7)->andReturn([
            'incomes' => [
                ['value' => '1200.00', 'date' => '2026-06-10'],
                ['value' => '300.00', 'date' => '2026-07-01'],
            ],
        ]);

        $financialPlanningService = Mockery::mock(FinancialPlanningService::class);
        $financialPlanningService->shouldReceive('getByUserIdWithOperations')->once()->with(7)->andReturn([
            'financialPlannings' => [
                [
                    'planId' => 9,
                    'operations' => [
                        ['accountId' => 4, 'totalProjectedValue' => 500, 'creationDate' => '2026-06-15'],
                        ['accountId' => 4, 'projectedValue' => 100, 'amount' => 2, 'creationDate' => '2026-06-20'],
                    ],
                ],
            ],
        ]);
        $financialPlanningService->shouldReceive('getAccountingAccounts')->once()->with(7)->andReturn([
            ['id' => 4, 'description' => 'Rent'],
        ]);

        $result = (new DashboardServices($expensesService, $incomesService, $financialPlanningService))
            ->getDashboardData(2026, 6, 7);

        $this->assertSame(1200.0, $result['incomeTotal']);
        $this->assertSame(700.0, $result['plannedExpensesTotal']);
        $this->assertSame(500.0, $result['remainingBudget']);
        $this->assertSame(550.0, $result['remainingAfterActualExpenses']);
        $this->assertSame(50.0, $result['projectionDifference']);
        $this->assertSame(2, $result['numberOfPlannedOperations']);
        $this->assertSame([
            [
                'accountId' => 4,
                'description' => 'Rent',
                'total' => 700.0,
                'count' => 2,
            ],
        ], $result['plannedExpensesByAccount']);
    }

    public function test_months_maps_month_numbers_to_names(): void
    {
        $expensesService = Mockery::mock(ExpensesService::class);
        $expensesService->shouldReceive('getMonthsAvailables')->once()->with(2026, null)->andReturn(new Collection([1, 12]));

        $result = (new DashboardServices($expensesService))->months(2026)->all();

        $this->assertSame([
            ['number' => 1, 'name' => 'January'],
            ['number' => 12, 'name' => 'December'],
        ], $result);
    }
}
