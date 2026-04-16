<?php

namespace App\Http\Controllers\AccountingAccountIncomes;

use Illuminate\Support\Facades\Http; 
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
    public function store(Request $request)
    {
        $user = $request->user();
        $baseUrl = config('services.spring_financial.base_url');
        print_r($request->all());

        $validated = $request->validate([

            'descripcion' => 'required|string|max:255',

        ]);

        $payload = [
            'userId' => $user,
            'description' => $validated['descripcion'],
            'isProjection' => true,
        ];


        $response = Http::acceptJson()
            ->asJson()
            ->put("{$baseUrl}/api/accounting-account/", $payload);

        print_r($response);
        /*
                if ($response->successful()) {
                    return print("Conexión establecida con el servicio de planificación financiera. Respuesta: " . $response->body());
                } else {
                    return print("Error al conectar con el servicio de planificación financiera. Código de estado: " . $response->status() . ". Respuesta: " . $response->body());
                }*/
    }

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

}

