<?php

namespace App\services;

use Carbon\Carbon;

class DashboardServices
{

    public function __construct(
        private ExpensesService $expensesService
    )
    {
        // Inicialización si es necesario
    }


    public function getDashboardData( $year = null, $month = null )
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
        
        return [
            'monthlyExpenses' => $monthlyExpenses,
            'totalExpenses' => $totalExpenses, 
            'numberOfExpenses' => count($monthlyExpenses),
            'expensesByAccount' => $expensesByAccount, 
            'monthName' => Carbon::create()->month($month)->format('F'),
            'month' => $month,
            'year' => $year,
        ];
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