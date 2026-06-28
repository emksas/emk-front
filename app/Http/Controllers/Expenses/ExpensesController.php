<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\services\AccountingAccountService;
use App\services\ExpensesService;
use App\services\FinancialPlanningService;
use Illuminate\Support\Facades\Auth;

class ExpensesController extends Controller
{

    private string $userId;

    public function __construct(
        private ExpensesService $expensesService,
        private AccountingAccountService $accountingAccountService,
        private FinancialPlanningService $financialPlanningService
    ) {
        $this->userId = Auth::id();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = $this->expensesService->getAllExpenses($this->userId);
        $error = null;
        $urlAuthEmail = $this->expensesService->getUrlAuthMicrosoft();
        return view('expenses.index', ['expenses' => $expenses, 'error' => $error, 'userId' => $this->userId, 'urlAuthEmail' => $urlAuthEmail]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $financialPlannings = $this->financialPlanningService->getByUserId($this->userId);
        $accountingAccounts = $this->accountingAccountService->getAllAccountingAccounts();
        return view('expenses.create', ['accountingAccounts' => $accountingAccounts, 'financialPlannings' => $financialPlannings]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        $this->expensesService->createExpense($request);
        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        abort_unless((int) $expense->user_id === (int) $this->userId, 404);

        $financialPlannings = $this->financialPlanningService->getByUserId($this->userId);
        $accountingAccounts = $this->accountingAccountService->getAllAccountingAccounts();
        return view('expenses.edit', ['expense' => $expense, 'accountingAccounts' => $accountingAccounts, 'financialPlannings' => $financialPlannings]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        abort_unless((int) $expense->user_id === (int) $this->userId, 404);

        $expense->update($request->validated());
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        try {
            $this->expensesService->deleteExpense($expense);
            return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('expenses.index')->with('error', 'Failed to delete expense: ' . $e->getMessage());
        }
    }

    public function getExpensesFromMail()
    {
        $userId = Auth::id();
        $this->expensesService->fromMail($userId);
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
