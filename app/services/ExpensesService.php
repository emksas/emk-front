<?php

namespace App\services;

use App\Http\Requests\StoreExpenseRequest;
use App\Models\Expense;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class ExpensesService
{

    private $baseUrl;

    public function fetchExpenses($user)
    {
        $this->baseUrl = config('services.nose_expenses.base_url');
        $expenses = [];

        try {
            $res = Http::timeout(10)->retry(3, 200)
                ->baseUrl($this->baseUrl.'/expenses/'.$user.'?folderPath=/Finanzas/rappi&numberElements=5')
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


    public function createExpense(StoreExpenseRequest $data)
    {
        $isValidated = $data->validated();
        if (!$isValidated) {
            throw new \InvalidArgumentException('Invalid data provided for creating an expense.');
        } else {
            return Expense::create($isValidated);
        }

    }

    public function fromMail($user)
    {
        $expensesFromMail = $this->fetchExpenses($user);
        foreach ($expensesFromMail as $expenseData) {
            if ($expenseData['paymentMethod'] != null) {

                $date = Carbon::parse($expenseData['transactionDate']);
                $data = [
                    'valor' => $expenseData['amount'],
                    'descripcion' => $expenseData['merchant'],
                    'fecha' => $date->format('Y-m-d H:i:s'),
                    'estado' => 'pay',
                    'idPlanificacion' => 1,
                    'cuentacontable_id' => 1,
                ];

                $rules = [
                    'valor' => ['required'],
                    'descripcion' => ['required', 'string', 'max:255'],
                    'fecha' => ['required'], // si viene en otro formato, ver abajo
                    'estado' => ['sometimes', 'string'],
                    'idPlanificacion' => ['sometimes', 'integer'],
                    'cuentacontable_id' => ['sometimes', 'integer'],
                ];
                $validated = Validator::make($data, $rules)->validate();
                Expense::create($validated);

            }
        }
    }

    public function getMonthlyExpenses($month, $year)
    {
        return Expense::whereMonth('fecha', $month)
            ->whereYear('fecha', $year)
            ->get()
            ->toArray();
    }

    public function getMonthlyExpensesByAccount($month, $year)
    {
        return Expense::join('cuentacontable as cc', 'cc.id', '=', 'egreso.cuentacontable_id')
            ->whereYear('egreso.fecha', $year)
            ->whereMonth('egreso.fecha', $month)
            ->groupBy('cc.id', 'cc.descripcion')
            ->select('cc.id as cuenta_id', 'cc.descripcion', DB::raw('SUM(egreso.valor) as total'))
            ->orderByDesc('total')
            ->get()
            ->toArray();

    }

    public function getSumOfExpensesByMonth($month, $year)
    {
        return Expense::whereMonth('fecha', $month)
            ->whereYear('fecha', $year)
            ->sum('valor');
    }


    public function getAllExpenses()
    {
        return Expense::all()->toArray();
    }

    public function deleteExpense(Expense $expense)
    {
        return $expense->delete();
    }

    public function getYersAvailables( ){
        return Expense::selectRaw('YEAR(fecha) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
    }

    public function getMonthsAvailables( $year ){
        return Expense::selectRaw('MONTH(fecha) as month')
            ->whereYear('fecha', intval($year))
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');
    }

}


?>