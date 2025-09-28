<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{

    public function monthlyExpenses(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');

        if (!$month || !$year) {
            return response()->json(['error' => 'Month and year parameters are required.'], 400);
        }

        $expensesService = app(\App\Services\ExpensesService::class);
        $expenses = $expensesService->getMonthlyExpenses($month, $year);
        $numberOfExpenses = count($expenses);
        $sumOfExpenses = $expensesService->getSumOfExpensesByMonth($month, $year);

        return response()->json(['numberOfExpenses' => $numberOfExpenses, 'sum' => $sumOfExpenses, 'data' => $expenses], 200);
    }

}


?>