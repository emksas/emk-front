<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Services\AccountingAccountService;
use App\Services\ExpensesService;

class ExpensesController extends Controller
{
    public function __construct(
        private ExpensesService $expensesService,
        private AccountingAccountService $accountingAccountService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = $this->expensesService->getAllExpenses();
        $error = null;

        return view('expenses.index', compact('expenses', 'error'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accountingAccounts = $this->accountingAccountService->getAllAccountingAccounts();
        return view('expenses.create', ['accountingAccounts' => $accountingAccounts]);
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
        print_r($expense);
        $accountingAccounts = $this->accountingAccountService->getAllAccountingAccounts();
        return view('expenses.edit', ['expense' => $expense, 'accountingAccounts' => $accountingAccounts]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
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
        $this->expensesService->fromMail();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

}