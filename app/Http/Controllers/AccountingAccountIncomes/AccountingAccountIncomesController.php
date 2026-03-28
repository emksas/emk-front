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
        return view('accountingAccountIncomes.create');
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
        return redirect()->route('accountingAccountIncomes.index')->with('success', 'Accounting Account updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountingAccount $accountingAccount)
    {
        try {
            $this->accountingAccountService->deleteAccountingAccount($accountingAccount);
            return redirect()->route('accountingAccountIncomes.index')->with('success', 'Accounting Account deleted');
        } catch (\Exception $e) {
            return redirect()->route('accountingAccountIncomes.index')->with('error', 'Error deleting Accounting Account: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $baseUrl = config('services.spring_financial.base_url');

        $validated = $request->validate([
            'valor' => 'required|numeric',
            'descripcion' => 'required|string|max:255',
            'fecha' => 'required|date',
            'cuentacontable_id' => 'required|integer',
        ]);

        /*$payload = [
            'valor'             => $validated['valor'],
            'descripcion'       => $validated['descripcion'],
            'fecha'             => $validated['fecha'],
            'cuentacontable_id' => $validated['cuentacontable_id'],
            'userId'            => $user->id,
        ];*/

        $payload = [
            'userId' => $user->id,
            'planName' => $validated['descripcion'], // o otro campo
            'description' => $validated['descripcion'],
            'projectedValue' => $validated['valor'],
            'projectedDate' => $validated['fecha'] . 'T00:00:00',
            'personalProject' => true
        ];

        print_r($payload);
    }
}

