<?php

namespace App\Http\Controllers\AccountingAccountIncomes;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAccountingAccountRequest;
use App\Models\AccountingAccount;
use Illuminate\Http\Request;
use App\services\AccountingAccountIncomesService;
use Illuminate\Support\Facades\Auth;

class AccountingAccountIncomesController extends Controller
{

    public function __construct(
        private AccountingAccountIncomesService $accountingAccountService
    ) {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accountingAccounts = $this->accountingAccountService->getAll();
        $error = null;

        return view('accountingAccountIncomes.index', [ 'accountingAccounts' => $accountingAccounts, 'error' => $error ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accountingAccountIncomes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $payload = [
            'userId' => Auth::id(),
            'description' => $validated['descripcion'],
            'projection' => true,
        ];


        $response = $this->accountingAccountService->create($payload);

        if ($response) {
            return redirect()->route('accountingAccountIncomes.index')->with('success', 'Accounting Account created');
        } else {
            return redirect()->route('accountingAccountIncomes.index')->with('error', 'We can not created');
        }
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
        return redirect()->route('accountingAccountIncomes.index')->with('success', 'Accounting Account updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountingAccount $accountingAccount)
    {
        try {
            $this->accountingAccountService->delete($accountingAccount);
            return redirect()->route('accountingAccountIncomes.index')->with('success', 'Accounting Account deleted');
        } catch (\Exception $e) {
            return redirect()->route('accountingAccountIncomes.index')->with('error', 'Error deleting Accounting Account: ' . $e->getMessage());
        }
    }
}
