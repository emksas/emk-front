<?php

namespace App\Http\Controllers\Incomes;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\services\AccountingAccountIncomesService;
use App\services\FinancialPlanningService;
use App\services\IncomesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomesController extends Controller
{
    public function __construct(
        private IncomesService $incomesService,
        private AccountingAccountIncomesService $accountingAccountService,
        private FinancialPlanningService $financialPlanningService
    ) {}

    public function index(Request $request)
    {
        $incomes = $this->incomesService->getIncomes($request->user());

        if ($incomes['status'] == 404) {
            return view('incomes.index', [
                'incomes' => [],
                'error' => 'No incomes found for the user',
                'status' => $incomes['status'],
            ]);
        } else if (isset($incomes['error'])) {
            return view('incomes.index', [
                'incomes' => [],
                'error' => $incomes['error'],
                'status' => $incomes['status'],
            ]);
        } else {
            return view('incomes.index', [
                'incomes' => $incomes['incomes'],
                'error' => null,
                'status' => 200,
            ]);
        }
    }

    public function create()
    {
        $accountingAccounts = $this->accountingAccountService->getAll();
        $financialPlannings = $this->financialPlanningService->getByUserId(Auth::id());
                
        return view('incomes.create', [
            'accountingAccounts' => $accountingAccounts,
            'financialPlannings' => $financialPlannings
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->input();
        dump($data);
        $this->incomesService->create($data, $request->user()->id);
        return redirect()->route('incomes.index')->with('success', 'Income created successfully.');
    }

    public function edit(Income $income)
    {
        $accountingAccounts = $this->accountingAccountService->getAll();
        $financialPlannings = $this->financialPlanningService->getByUserId(Auth::id());
        
        return view('incomes.edit', [
            'income' => $income,
            'accountingAccounts' => $accountingAccounts,
            'financialPlannings' => $financialPlannings
        ]);
    }

    public function update(Request $request, Income $income)
    {
        $incomeInformation = $income->toArray();
        $this->incomesService->updateIncome($incomeInformation, $request->input());
        return redirect()->route('incomes.index')->with('success', 'Income updated successfully.');
    }

    public function destroy(Request $request, Income $income)
    {
        try {
            $this->incomesService->deleteIncome($income['id']);
            return redirect()->route('incomes.index')->with('success', 'Income deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('incomes.index')->with('error', 'Failed to delete income: ' . $e->getMessage());
        }
    }
}
