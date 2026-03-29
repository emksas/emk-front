<?php

namespace App\Http\Controllers\Incomes;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\services\AccountingAccountService;
use App\services\IncomesService;
use Illuminate\Http\Request;

class IncomesController extends Controller
{
    public function __construct(
        private IncomesService $incomesService,
        private AccountingAccountService $accountingAccountService
    ) {
    }

    public function index()
    {
        $incomes = $this->incomesService->getAllIncomes();
        $error = null;

        return view('incomes.index', compact('incomes', 'error'));
    }

    public function create()
    {
        $accountingAccounts = $this->accountingAccountService->getAllAccountingAccounts();
        return view('incomes.create', ['accountingAccounts' => $accountingAccounts]);
    }

    public function store(Request $request)
    {
        $this->incomesService->createIncome($request);
        return redirect()->route('incomes.index')->with('success', 'Income created successfully.');
    }

    public function edit(Income $income)
    {
        $accountingAccounts = $this->accountingAccountService->getAllAccountingAccounts();
        return view('incomes.edit', [
            'income' => $income,
            'accountingAccounts' => $accountingAccounts
        ]);
    }

    public function update(Request $request, Income $income)
    {
        $income->update($request->all());
        return redirect()->route('incomes.index')->with('success', 'Income updated successfully.');
    }

    public function destroy(Income $income)
    {
        try {
            $this->incomesService->deleteIncome($income);
            return redirect()->route('incomes.index')->with('success', 'Income deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('incomes.index')->with('error', 'Failed to delete income: ' . $e->getMessage());
        }
    }
}