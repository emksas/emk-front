<?php

namespace App\Http\Controllers\FinancialPlanning;

use App\Http\Controllers\Controller;
use App\services\AccountingAccountService;
use App\services\FinancialPlanningService;
use Illuminate\Http\Request;

class FinancialPlanningController extends Controller
{
    public function __construct(
        private AccountingAccountService $accountingAccountService,
        private FinancialPlanningService $financialPlanningService
    ) {}

    public function index(Request $request)
    {
        $result = $this->financialPlanningService->getByUserIdWithOperations($request->user()->id);

        return view('financial-planning.index', [
            'financialPlannings' => $result['financialPlannings'],
            'error' => $result['error'],
            'spring_status' => $result['spring_status'],
        ]);
    }

    public function create()
    {
        $accountingAccounts = $this->accountingAccountService->getAllAccountingAccounts();

        if (empty($accountingAccounts)) {
            return redirect()->route('financial-planning.index')
                ->with('error', 'You need to have accounting accounts before creating a planned operation.');
        }

        return view('financial-planning.create', ['accountingAccounts' => $accountingAccounts]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateFinancialPlanning($request);
        $response = $this->financialPlanningService->create($validated, $request->user()->id);

        if ($response->successful()) {
            return redirect()->route('financial-planning.index')
                ->with('success', 'Financial plan created successfully.');
        }

        $this->financialPlanningService->reportFailedResponse($response, 'create');

        return redirect()->route('financial-planning.index')
            ->with('error', 'Error creating financial plan: ' . $response->status());
    }

    public function show(string $financial_planning)
    {
        return redirect()->route('financial-planning.index');
    }

    public function edit(Request $request, string $financial_planning)
    {
        $plan = $this->financialPlanningService->getByPlanId($financial_planning, $request->user()->id);

        if ($plan === null) {
            return redirect()->route('financial-planning.index')
                ->with('error', 'Error fetching data from financial planning service');
        }

        return view('financial-planning.edit', ['financialPlanning' => $plan]);
    }

    public function update(Request $request, string $financial_planning)
    {
        $validated = $this->validateFinancialPlanning($request);
        $response = $this->financialPlanningService->update($financial_planning, $request->user()->id, $validated);

        if ($response->successful()) {
            return redirect()->route('financial-planning.index')
                ->with('success', 'Financial plan updated successfully.');
        }

        $this->financialPlanningService->reportFailedResponse($response, 'update');

        return redirect()->route('financial-planning.index')
            ->with('error', 'Error updating financial plan: ' . $response->status());
    }

    public function destroy(Request $request, string $financial_planning)
    {
        $response = $this->financialPlanningService->delete($financial_planning, $request->user()->id);

        if ($response->failed()) {
            $this->financialPlanningService->reportFailedResponse($response, 'delete');

            return redirect()->route('financial-planning.index')
                ->with('error', 'Error deleting plan from financial planning service');
        }

        return redirect()->route('financial-planning.index')
            ->with('success', 'Financial plan deleted successfully.');
    }

    private function validateFinancialPlanning(Request $request): array
    {
        return $request->validate([
            'valor' => ['required', 'numeric'],
            'descripcion' => ['required', 'string', 'max:255'],
            'fecha' => ['required', 'date'],
        ]);
    }
}
