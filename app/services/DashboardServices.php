<?php

namespace App\Services;

use Carbon\Carbon;

class DashboardServices
{

    public function __construct(
        private ExpensesService $expensesService
    )
    {
        // Inicialización si es necesario
    }


    public function getDashboardData()
    {
        
        /*
        $now = Carbon::now(); 
        $monthlyExpenses = $this->expensesService->getMonthlyExpenses($now->year, $now->month);
        $totalExpenses = $this->expensesService->getSumOfExpensesByMonth($now->month, $now->year);
        $expensesByAccount = $this->expensesService->getMonthlyExpensesByAccount($now->month, $now->year);
        */

        $monthlyExpenses = $this->expensesService->getMonthlyExpenses(9, 2025);
        $totalExpenses = $this->expensesService->getSumOfExpensesByMonth(9, 2025);
        $expensesByAccount = $this->expensesService->getMonthlyExpensesByAccount(9, 2025);

        return [
            'monthlyExpenses' => $monthlyExpenses,
            'totalExpenses' => $totalExpenses, 
            'numberOfExpenses' => count($monthlyExpenses),
            'expensesByAccount' => $expensesByAccount
        ];
    }
}

?> 