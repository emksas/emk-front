<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AccountingAccountService;

class PlannedOperationController extends Controller
{

    public function __construct(
        private AccountingAccountService $accountingAccountService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accountingAccounts = $this->accountingAccountService->getAllAccountingAccounts();

        if (empty($accountingAccounts)) {
            return redirect()->route('accountingAccount.create')
                ->with('error', 'Please create an accounting account before adding a planned operation.');
        } else {
            return view('plannedOperation.create', ['accountingAccounts' => $accountingAccounts]);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id, string $planId)
    {
        $user = $request->user();
        $baseUrl = config('services.spring_financial.base_url');

        // 1) Validar lo que vas a enviar (ajusta reglas según tu necesidad)
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'accountId' => ['required', 'integer'],
            'projectedValue' => ['nullable', 'numeric'],
            'amount' => ['nullable', 'numeric'],
            'totalProjectedValue' => ['nullable', 'numeric'],
            // 'dueDate'            => ['nullable', 'date'], // si luego lo habilitas en Java
        ]);

        // 2) Armar payload tal cual lo espera Java (mismos nombres)
        $payload = [
            'description' => $validated['description'],
            'accountId' => $validated['accountId'],
            // 'dueDate'           => $validated['dueDate'] ?? null,
            'projectedValue' => $validated['projectedValue'] ?? null,
            'amount' => $validated['amount'] ?? null,
            'totalProjectedValue' => $validated['totalProjectedValue'] ?? null,
        ];

        // 3) Enviar POST al microservicio
        $response = Http::acceptJson()
            ->asJson()
            ->post($baseUrl . "/user/{$user->id}/plan/{$planId}/operation/{$id}", $payload);

        // 4) Manejo de respuesta
        if ($response->successful()) {
            return redirect()
                ->back()
                ->with('success', 'Operation created successfully.');
        }

        // Si quieres ver el error exacto del microservicio:
        // dd($response->status(), $response->body());

        return redirect()
            ->back()
            ->with('error', 'Error creating operation: ' . $response->status());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id, string $planId)
    {

        $user = $request->user();

        $baseUrl = config('services.spring_financial.base_url');

        $response = Http::get($baseUrl . '/user/' . $user->id . '/plan/' . $planId . '/operation/' . $id);

        if ($response->failed()) {
            return redirect()->route('financial-planning.index')->with('error', 'Error getting planned operation ');
        }

        return view('plannedOperation.edit', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id, string $planId)
    {
        $user = $request->user();

        $baseUrl = config('services.spring_financial.base_url');

        $response = Http::delete($baseUrl . '/user/' . $user->id . '/plan/' . $planId . '/operation/' . $id);

        if ($response->failed()) {
            return redirect()->route('financial-planning.index')->with('error', 'Error deleting plan from financial planning service');
        }

        return redirect()->route('financial-planning.index')->with('success', 'Planning operation deleted successfully.');
    }
}
