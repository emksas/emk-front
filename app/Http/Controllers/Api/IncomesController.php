<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IncomesController extends Controller
{
    public function monthlyIncomes(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');

        if (!$month || !$year) {
            return response()->json(['error' => 'Month and year parameters are required.'], 400);
        }

        $incomesService = app(\App\services\IncomesService::class);
        $incomes = $incomesService->getMonthlyIncomes($month, $year);
        $numberOfIncomes = count($incomes);
        $sumOfIncomes = $incomesService->getSumOfIncomesByMonth($month, $year);

        return response()->json([
            'numberOfIncomes' => $numberOfIncomes,
            'sum' => $sumOfIncomes,
            'data' => $incomes
        ], 200);
    }

    public function createIncome(Request $request)
    {
        $incomesService = app(\App\services\IncomesService::class);

        try {
            $income = $incomesService->createIncome($request);
            return response()->json(['data' => $income], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the income.'], 500);
        }
    }
}