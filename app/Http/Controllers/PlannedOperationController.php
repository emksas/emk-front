<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\services\AccountingAccountService;
use Illuminate\Support\Facades\Http;


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
    public function create(string $planId)
    {

        $accountingAccounts = $this->accountingAccountService->getAllAccountingAccounts();

        if (empty($accountingAccounts)) {
            return redirect()->route('accountingAccount.create')
                ->with('error', 'Please create an accounting account before adding a planned operation.');
        } else {
            return view('plannedOperation.create', ['accountingAccounts' => $accountingAccounts, 'planId' => $planId]);
        }

    }

    public function store(Request $request, string $planId)
    {

        $user = $request->user();
        $baseUrl = config('services.spring_financial.base_url');

        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'accountId' => ['required', 'integer'],
            'projectedValue' => ['nullable', 'numeric'],
            'amount' => ['nullable', 'numeric'],
            'totalProjectedValue' => ['nullable', 'numeric'],
            // 'dueDate'            => ['nullable', 'date'], // si luego lo habilitas en Java
        ]);

        $payload = [
            'description' => $validated['description'],
            'accountId' => $validated['accountId'],
            // 'dueDate'           => $validated['dueDate'] ?? null,
            'projectedValue' => $validated['projectedValue'] ?? null,
            'amount' => $validated['amount'] ?? null,
            'totalProjectedValue' => $validated['totalProjectedValue'] ?? null,
        ];


        $response = Http::acceptJson()
            ->asJson()
            ->post("{$baseUrl}/api/planned-operations/user/{$user->id}/plan/{$planId}", $payload);

        if ($response->successful()) {
            return redirect()->route('financial-planning.index')
                ->with('success', 'Operation created successfully.');
        } else {
            return redirect()->route('planning-operation.index')
                ->with('error', 'Error creating operation: ' . $response->status());
        }
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
    public function edit(Request $request, string $planningId, string $transactionId)
    {

        $user = $request->user();

        $baseUrl = config('services.spring_financial.base_url');

        $response = Http::get($baseUrl . '/api/planned-operations/user/' . $user->id . '/plan/' . $planningId . '/operation/' . $transactionId);

        if ($response->failed()) {
            return redirect()->route('financial-planning.index')->with('error', 'Error getting planned operation ');
        }

        return view('plannedOperation.edit', ['plannedOperation' => $response->json(), 'accountingAccounts' => $this->accountingAccountService->getAllAccountingAccounts()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $planningId, string $transactionId)
    {
        $user = $request->user();

        $baseUrl = config('services.spring_financial.base_url');

        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'accountId' => ['required', 'integer'],
            'projectedValue' => ['nullable', 'numeric'],
            'amount' => ['nullable', 'numeric'],
            'totalProjectedValue' => ['nullable', 'numeric'],
            // 'dueDate'            => ['nullable', 'date'], // si luego lo habilitas en Java
        ]);

        $payload = [
            'description' => $validated['description'],
            'accountId' => $validated['accountId'],
            // 'dueDate'           => $validated['dueDate'] ?? null,
            'projectedValue' => $validated['projectedValue'] ?? null,
            'amount' => $validated['amount'] ?? null,
            'totalProjectedValue' => $validated['totalProjectedValue'] ?? null,
        ];

        $response = Http::acceptJson()
            ->asJson()
            ->put("{$baseUrl}/api/planned-operations/user/{$user->id}/plan/{$planningId}/operation/{$transactionId}", $payload);

        if ($response->successful()) {
            return redirect()->route('financial-planning.index')
                ->with('success', 'Operation updated successfully.');
        } else {
            return redirect()->route('planning-operation.index')
                ->with('error', 'Error updating operation: ' . $response->status());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $planningId, string $transactionId)
    {
        $user = $request->user();

        $baseUrl = config('services.spring_financial.base_url');

        $response = Http::delete($baseUrl . '/api/planned-operations/user/' . $user->id . '/plan/' . $planningId . '/operation/' . $transactionId);

        if ($response->failed()) {
            return redirect()->route('financial-planning.index')->with('error', 'Error deleting plan from financial planning service');
        }

        return redirect()->route('financial-planning.index')->with('success', 'Planning operation deleted successfully.');

    }
}
