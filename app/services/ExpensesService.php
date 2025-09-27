<?php

namespace App\Services;

use App\Http\Requests\StoreExpenseRequest;
use App\Models\Expense;
use Illuminate\Support\Facades\Http;

class ExpensesService
{


    public function fetchExpenses()
    {
        $expenses = [];

        try {
            $res = Http::timeout(10)->retry(3, 200)
                ->baseUrl('http://localhost:3000/api')
                ->get('/expenses')
                ->throw()
                ->json();

            $expenses = $res['data'] ?? $res;

        } catch (\Exception $e) {
            report($e);
            return false;
        }

        return $expenses;
    }


    public function createExpense( StoreExpenseRequest $data)
    {
        $isValidated = $data->validated();
        print_r($isValidated);
        if (!$isValidated) {
            throw new \InvalidArgumentException('Invalid data provided for creating an expense.');
        } else {
            return Expense::create($isValidated);
        }

    }


    public function getAllExpenses()
    {
        return Expense::all()->toArray();
    }



}


?>