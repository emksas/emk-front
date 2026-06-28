<?php

namespace App\services;

use App\Http\Requests\StoreExpenseRequest;
use App\Models\Expense;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExpensesService
{

    private $baseUrl;

    public function fetchExpenses($user)
    {
        $this->baseUrl = config('services.node_expenses.base_url');
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
            $isValidated['user_id'] = Auth::id();
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
                    'planificacion_financiera_id' => 1,
                    'cuentacontable_id' => 1,
                    'user_id' => $user,
                ];

                $rules = [
                    'valor' => ['required'],
                    'descripcion' => ['required', 'string', 'max:255'],
                    'fecha' => ['required'], // si viene en otro formato, ver abajo
                    'estado' => ['sometimes', 'string'],
                    'planificacion_financiera_id' => ['sometimes', 'integer'],
                    'cuentacontable_id' => ['sometimes', 'integer'],
                    'user_id' => ['required', 'integer'],
                ];
                $validated = Validator::make($data, $rules)->validate();
                Expense::create($validated);

            }
        }
    }

    public function getMonthlyExpenses($month, $year, string|int|null $userId = null)
    {
        return Expense::whereMonth('fecha', $month)
            ->whereYear('fecha', $year)
            ->where('user_id', $this->resolveUserId($userId))
            ->get()
            ->toArray();
    }

    public function getMonthlyExpensesByAccount($month, $year, string|int|null $userId = null)
    {
        return Expense::join('cuentacontable as cc', 'cc.id', '=', 'egreso.cuentacontable_id')
            ->whereYear('egreso.fecha', $year)
            ->whereMonth('egreso.fecha', $month)
            ->where('egreso.user_id', $this->resolveUserId($userId))
            ->groupBy('cc.id', 'cc.descripcion')
            ->select('cc.id as cuenta_id', 'cc.descripcion', DB::raw('SUM(egreso.valor) as total'))
            ->orderByDesc('total')
            ->get()
            ->toArray();

    }

    public function getSumOfExpensesByMonth($month, $year, string|int|null $userId = null)
    {
        return Expense::whereMonth('fecha', $month)
            ->whereYear('fecha', $year)
            ->where('user_id', $this->resolveUserId($userId))
            ->sum('valor');
    }


    public function getAllExpenses(string|int|null $userId = null)
    {
        return Expense::where('user_id', $this->resolveUserId($userId))->get()->toArray();
    }

    public function deleteExpense(Expense $expense)
    {
        if ((int) $expense->user_id !== (int) Auth::id()) {
            throw new \RuntimeException('You are not allowed to delete this expense.');
        }

        return $expense->delete();
    }

    public function getYersAvailables(string|int|null $userId = null){
        return Expense::selectRaw('YEAR(fecha) as year')
            ->where('user_id', $this->resolveUserId($userId))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
    }

    public function getMonthsAvailables( $year, string|int|null $userId = null ){
        return Expense::selectRaw('MONTH(fecha) as month')
            ->whereYear('fecha', intval($year))
            ->where('user_id', $this->resolveUserId($userId))
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');
    }

    public function getBaseUrl(){
        return $this->baseUrl;
    }

    public function getUrlAuthMicrosoft(): string{
        $this->baseUrl = config('services.node_expenses.base_url');
        $returnTo = rtrim(config('app.url'), '/') . '/microsoft/auth/callback';

        return  $this->baseUrl . '/auth/login/' . Auth::id()
        . '?returnTo=' . urlencode($returnTo);
    }

    private function resolveUserId(string|int|null $userId): int
    {
        $resolvedUserId = $userId ?? Auth::id();

        if ($resolvedUserId === null) {
            throw new \RuntimeException('Authenticated user is required.');
        }

        return (int) $resolvedUserId;
    }

}
