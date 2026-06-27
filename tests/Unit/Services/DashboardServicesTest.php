<?php

namespace Tests\Unit\Services;

use App\services\DashboardServices;
use App\services\ExpensesService;
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
        $expensesService->shouldReceive('getMonthlyExpenses')->once()->with(6, 2026)->andReturn([
            ['valor' => 100],
            ['valor' => 200],
        ]);
        $expensesService->shouldReceive('getSumOfExpensesByMonth')->once()->with(6, 2026)->andReturn(300);
        $expensesService->shouldReceive('getMonthlyExpensesByAccount')->once()->with(6, 2026)->andReturn([
            ['descripcion' => 'Food', 'total' => 300],
        ]);

        $result = (new DashboardServices($expensesService))->getDashboardData(2026, 6);

        $this->assertSame(300, $result['totalExpenses']);
        $this->assertSame(2, $result['numberOfExpenses']);
        $this->assertSame('June', $result['monthName']);
        $this->assertSame(6, $result['month']);
        $this->assertSame(2026, $result['year']);
    }

    public function test_months_maps_month_numbers_to_names(): void
    {
        $expensesService = Mockery::mock(ExpensesService::class);
        $expensesService->shouldReceive('getMonthsAvailables')->once()->with(2026)->andReturn(new Collection([1, 12]));

        $result = (new DashboardServices($expensesService))->months(2026)->all();

        $this->assertSame([
            ['number' => 1, 'name' => 'January'],
            ['number' => 12, 'name' => 'December'],
        ], $result);
    }
}
