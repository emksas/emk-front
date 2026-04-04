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

    public function index(Request $request)
    {
        print_r("prueba url python service: ");
        $base_url = config('services.python_incomes.base_url');
        print_r($base_url);
        /*
        $incomes = $this->incomesService->getIncomes( $request->user() );

        dd($incomes);

        
        if($incomes['spring_status'] == 404){
            return view('incomes.index', [
                'incomes' => [],
                'error' => 'No incomes found for the user',
                'spring_status' => $incomes['spring_status'],
            ]);
        } else if (isset($incomes['error'])) {
            return view('incomes.index', [
                'incomes' => [],
                'error' => $incomes['error'],
                'spring_status' => $incomes['spring_status'],
            ]);
        } else {
            return view('incomes.index', [
                'incomes' => $incomes,
                'error' => null,
                'spring_status' => 200,
            ]);
        }
            */
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