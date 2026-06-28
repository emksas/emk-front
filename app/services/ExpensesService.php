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

    private string $baseUrl;

    public function __construct(
        private FinancialPlanningService $financialPlanningService,
        private AccountingAccountService $accountingAccountService
    ) {}

    public function fetchExpenses(string $user, string $folderPath)
    {

        $this->baseUrl = config('services.node_expenses.base_url');
        dump($this->baseUrl);
        $expenses = [];

        try {
            $res = Http::timeout(10)->retry(3, 200)
                ->baseUrl($this->baseUrl)
                ->get('/expenses/' . $user, [
                    'folderPath' => $folderPath,
                    'numberElements' => 5,
                ])
                ->throw()
                ->json();

            dump($res);

            $expenses = collect($res['expenses'] ?? $res['data'] ?? $res)
                ->map(fn($expense) => $this->formatExpenseFromNode($expense))
                ->filter()
                ->values()
                ->all();
        } catch (\Exception $e) {
            report($e);
        }

        return $expenses;
    }

    private function formatExpenseFromNode(array $expense): ?array
    {
        $expenseData = $expense['bodyText'] ?? $expense;

        if (
            empty($expenseData['amount']) ||
            empty($expenseData['paymentMethod']) ||
            empty($expenseData['transactionDate'])
        ) {
            return null;
        }

        return [
            'messageId' => $expense['messageId'] ?? $expense['id'] ?? null,
            'amount' => $expenseData['amount'],
            'paymentMethod' => $expenseData['paymentMethod'],
            'authorizationCode' => $expenseData['authorizationCode'] ?? null,
            'merchant' => $expenseData['merchant'] ?? $expense['subject'] ?? 'Sin comercio',
            'transactionDate' => $expenseData['transactionDate'],
        ];
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

    public function fromMail($user, string $folderPath)
    {
        dump($user);
        dump($folderPath);
        $expensesFromMail = $this->fetchExpenses($user, $folderPath);
        dump($expensesFromMail);

        if (!empty($expensesFromMail)) {

            $financialPlannings = $this->financialPlanningService->getByUserId($user);
            $accountingAccounts = $this->accountingAccountService->getAllAccountingAccounts();

            dump($financialPlannings);

            if (!empty($financialPlannings) && !empty($accountingAccounts)) {
                foreach ($expensesFromMail as $expenseData) {
                    if (!empty($expenseData['paymentMethod']) && !empty($expenseData['transactionDate'])) {

                        $date = Carbon::parse($expenseData['transactionDate']);
                        $data = [
                            'valor' => $expenseData['amount'],
                            'descripcion' => $expenseData['merchant'] ?? 'Sin comercio',
                            'fecha' => $date->format('Y-m-d H:i:s'),
                            'estado' => 'pay',
                            'planificacion_financiera_id' => $financialPlannings[0]['planId'],
                            'cuentacontable_id' => $accountingAccounts[0]['id'],
                        ];

                        dump($data);

                        $rules = [
                            'valor' => ['required'],
                            'descripcion' => ['required', 'string', 'max:255'],
                            'fecha' => ['required'], // si viene en otro formato, ver abajo
                            'estado' => ['sometimes', 'string'],
                            'planificacion_financiera_id' => ['sometimes', 'integer'],
                            'cuentacontable_id' => ['sometimes', 'integer'],
                        ];
                        $validated = Validator::make($data, $rules)->validate();
                        Expense::create($validated);
                    }
                }
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

    public function getYersAvailables()
    {
        return Expense::selectRaw('YEAR(fecha) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
    }

    public function getMonthsAvailables($year)
    {
        return Expense::selectRaw('MONTH(fecha) as month')
            ->whereYear('fecha', intval($year))
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function getUrlAuthMicrosoft(): string
    {
        $this->baseUrl = config('services.node_expenses_external.base_url');
        $returnTo = rtrim(config('app.url'), '/');

        return  $this->baseUrl . '/auth/login/' . Auth::id()
            . '?returnTo=' . urlencode($returnTo);
    }
}
