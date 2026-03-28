<?php

namespace App\Http\Controllers\AccountingAccountIncomes;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountingAccountRequest;
use App\Http\Requests\UpdateAccountingAccountRequest;
use App\Models\AccountingAccount;
use Illuminate\Http\Request;
use App\services\AccountingAccountService;

class AccountingAccountIncomesController extends Controller
{


    public function __construct(private AccountingAccountService $accountingAccountService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accountingAccounts = $this->accountingAccountService->getAllAccountingAccounts();
        $error = null;

        return view('accountingAccountIncomes.index', compact('accountingAccounts', 'error'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accountingAccountIncome.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountingAccountRequest $request)
    {
        $this->accountingAccountService->createAccountingAccount($request);
        return redirect()->route('accountingAccountIncomes.index')->with('success', 'Accounting Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountingAccount $accountingAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountingAccount $accountingAccount)
    {
        return view('accountingAccountIncomes.edit', ['accountingAccount' => $accountingAccount]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountingAccountRequest $request, AccountingAccount $accountingAccount)
    {
        $accountingAccount->update($request->validated());
        return redirect()->route('accountingAccount.index')->with('success', 'Accounting Account updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountingAccount $accountingAccount)
    {
        try {
            $this->accountingAccountService->deleteAccountingAccount($accountingAccount);
            return redirect()->route('accountingAccount.index')->with('success', 'Accounting Account deleted');
        } catch (\Exception $e) {
            return redirect()->route('accountingAccount.index')->with('error', 'Error deleting Accounting Account: ' . $e->getMessage());
        }
    }
}
