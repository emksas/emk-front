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

        $expensesService = app(\App\services\ExpensesService::class);
        $expenses = $expensesService->getMonthlyExpenses($month, $year);
        $numberOfExpenses = count($expenses);
        $sumOfExpenses = $expensesService->getSumOfExpensesByMonth($month, $year);

        return response()->json(['numberOfExpenses' => $numberOfExpenses, 'sum' => $sumOfExpenses, 'data' => $expenses], 200);
    }

    public function createExpense(Request $request)
    {
        $expensesService = app(\App\services\ExpensesService::class);
        try {
            $expense = $expensesService->createExpense($request);
            return response()->json(['data' => $expense], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the expense.'], 500);
        }
    }

    public function fetchExpensesFromMail()
    {
        $expensesService = app(\App\services\ExpensesService::class);
        $result = $expensesService->fromMail();
        if ($result === false) {
            return response()->json(['error' => 'Failed to fetch expenses from mail.'], 500);
        }
        return response()->json(['message' => 'Expenses fetched and stored successfully.'], 200);
    }

}


?>