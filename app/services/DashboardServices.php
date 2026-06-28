<?php

namespace App\services;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class DashboardServices
{

    public function __construct(
        private ExpensesService $expensesService,
        private ?IncomesService $incomesService = null,
        private ?FinancialPlanningService $financialPlanningService = null
    )
    {
    }


    public function getDashboardData( $year = null, $month = null, string|int|null $userId = null )
    {
        
        
        $now = Carbon::now();
        if ($year == null && $month == null) {
            $year = $now->year;
            $month = $now->month;
        }

        $month = intval($month);
        $year = intval($year);
        $monthlyExpenses = $this->expensesService->getMonthlyExpenses($month, $year);
        $totalExpenses = $this->expensesService->getSumOfExpensesByMonth($month, $year);
        $expensesByAccount = $this->expensesService->getMonthlyExpensesByAccount($month, $year);
        $personalSummary = $userId !== null
            ? $this->getPersonalFinanceSummary($userId, $month, $year)
            : $this->emptyPersonalFinanceSummary();
        
        return [
            'monthlyExpenses' => $monthlyExpenses,
            'totalExpenses' => $totalExpenses, 
            'numberOfExpenses' => count($monthlyExpenses),
            'expensesByAccount' => $expensesByAccount, 
            'incomeTotal' => $personalSummary['incomeTotal'],
            'monthlyIncomes' => $personalSummary['monthlyIncomes'],
            'numberOfIncomes' => count($personalSummary['monthlyIncomes']),
            'plannedExpensesTotal' => $personalSummary['plannedExpensesTotal'],
            'plannedOperations' => $personalSummary['plannedOperations'],
            'numberOfPlannedOperations' => count($personalSummary['plannedOperations']),
            'plannedExpensesByAccount' => $personalSummary['plannedExpensesByAccount'],
            'remainingBudget' => $personalSummary['incomeTotal'] - $personalSummary['plannedExpensesTotal'],
            'remainingAfterActualExpenses' => $personalSummary['incomeTotal'] - $totalExpenses,
            'projectionDifference' => $personalSummary['plannedExpensesTotal'] - $totalExpenses,
            'monthName' => Carbon::create()->month($month)->format('F'),
            'month' => $month,
            'year' => $year,
        ];
    }

    private function getPersonalFinanceSummary(string|int $userId, int $month, int $year): array
    {
        $incomes = $this->incomesService()->getIncomes($userId)['incomes'] ?? [];
        $monthlyIncomes = array_values(array_filter($incomes, function (array $income) use ($month, $year) {
            $date = $income['date'] ?? $income['fecha'] ?? null;

            if ($date === null) {
                return false;
            }

            try {
                $incomeDate = Carbon::parse($date);
            } catch (\Throwable) {
                return false;
            }

            return $incomeDate->month === $month && $incomeDate->year === $year;
        }));

        $incomeTotal = array_reduce($monthlyIncomes, function (float $total, array $income) {
            return $total + $this->moneyValue($income['value'] ?? $income['valor'] ?? 0);
        }, 0.0);

        $plans = $this->financialPlanningService()->getByUserIdWithOperations($userId)['financialPlannings'] ?? [];
        $plannedOperations = $this->flattenPlannedOperations($plans, $month, $year);
        $accountsById = $this->accountsById($this->financialPlanningService()->getAccountingAccounts($userId));

        $plannedExpensesTotal = array_reduce($plannedOperations, function (float $total, array $operation) {
            return $total + $this->plannedOperationTotal($operation);
        }, 0.0);

        return [
            'incomeTotal' => $incomeTotal,
            'monthlyIncomes' => $monthlyIncomes,
            'plannedExpensesTotal' => $plannedExpensesTotal,
            'plannedOperations' => $plannedOperations,
            'plannedExpensesByAccount' => $this->plannedExpensesByAccount($plannedOperations, $accountsById),
        ];
    }

    private function flattenPlannedOperations(array $plans, int $month, int $year): array
    {
        $operations = [];

        foreach ($plans as $plan) {
            foreach (($plan['operations'] ?? []) as $operation) {
                if ($this->plannedOperationBelongsToPeriod($operation, $month, $year)) {
                    $operations[] = $operation;
                }
            }
        }

        return $operations;
    }

    private function plannedOperationBelongsToPeriod(array $operation, int $month, int $year): bool
    {
        $date = $operation['dueDate'] ?? $operation['creationDate'] ?? null;

        if ($date === null) {
            return true;
        }

        try {
            $operationDate = Carbon::parse($date);
        } catch (\Throwable) {
            return true;
        }

        return $operationDate->month === $month && $operationDate->year === $year;
    }

    private function accountsById(array $accounts): array
    {
        $accountsById = [];

        foreach ($accounts as $account) {
            $id = $account['id'] ?? null;

            if ($id === null) {
                continue;
            }

            $accountsById[(int) $id] = $account['description']
                ?? $account['descripcion']
                ?? "Account {$id}";
        }

        return $accountsById;
    }

    private function plannedExpensesByAccount(array $operations, array $accountsById): array
    {
        $totals = [];

        foreach ($operations as $operation) {
            $accountId = (int) ($operation['accountId'] ?? 0);
            $accountName = $accountsById[$accountId] ?? "Account {$accountId}";

            if (!isset($totals[$accountId])) {
                $totals[$accountId] = [
                    'accountId' => $accountId,
                    'description' => $accountName,
                    'total' => 0.0,
                    'count' => 0,
                ];
            }

            $totals[$accountId]['total'] += $this->plannedOperationTotal($operation);
            $totals[$accountId]['count']++;
        }

        $totals = array_values($totals);
        usort($totals, fn (array $left, array $right) => $right['total'] <=> $left['total']);

        return $totals;
    }

    private function plannedOperationTotal(array $operation): float
    {
        $total = Arr::get($operation, 'totalProjectedValue');

        if ($total !== null && $total !== '') {
            return $this->moneyValue($total);
        }

        return $this->moneyValue($operation['projectedValue'] ?? 0) * (int) ($operation['amount'] ?? 1);
    }

    private function moneyValue(mixed $value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        $normalized = str_replace(['$', ' ', '.'], '', (string) $value);
        $normalized = str_replace(',', '.', $normalized);

        return is_numeric($normalized) ? (float) $normalized : 0.0;
    }

    private function emptyPersonalFinanceSummary(): array
    {
        return [
            'incomeTotal' => 0.0,
            'monthlyIncomes' => [],
            'plannedExpensesTotal' => 0.0,
            'plannedOperations' => [],
            'plannedExpensesByAccount' => [],
        ];
    }

    private function incomesService(): IncomesService
    {
        return $this->incomesService ??= new IncomesService();
    }

    private function financialPlanningService(): FinancialPlanningService
    {
        return $this->financialPlanningService ??= new FinancialPlanningService();
    }

    public function yearsAvailables(){
        return $this->expensesService->getYersAvailables();
    }

    public function months($year){

        $months = $this->expensesService->getMonthsAvailables($year);
        return $months->map(function($month) {
            return [
                'number' => $month,
                'name' => Carbon::create()->month($month)->format('F')
            ];
        });
    }
}

?>
