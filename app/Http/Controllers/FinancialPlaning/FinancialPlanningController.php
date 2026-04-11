<?php

namespace App\Http\Controllers\FinancialPlaning;


use App\Http\Requests\StoreFinancialPlanningRequest;
use App\Http\Requests\UpdateFinancialPlanningRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\services\AccountingAccountService;

class FinancialPlanningController extends Controller
{

    public function __construct(
        private AccountingAccountService $accountingAccountService
    ) {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $baseUrl = config('services.spring_financial.base_url');

        $response = Http::get($baseUrl . '/api/financial-planning/user/' . $user->id);



        if ($response->failed()) {
            return view('financial-planning.index', [
                'financialPlannings' => [],
                'error' => 'Error fetching data from financial planning service',
                'spring_status' => $response->status(),
            ]);
        }

        $financialPlannings = $response->json();

        foreach ($financialPlannings as $key => $financialPlanning) {
            $operationsResponse = Http::get($baseUrl . '/api/planned-operations/user/' . $user->id . '/plan/' . $financialPlanning['planId']);
            if ($operationsResponse->successful()) {
                $financialPlannings[$key]['operations'] = $operationsResponse->json();
            } else {
                $financialPlannings[$key]['operations'] = [];
            }
        }

        return view('financial-planning.index', [
            'financialPlannings' => $financialPlannings
        ]);

    }

    public function create()
    {

        $accountingAccounts = $this->accountingAccountService->getAllAccountingAccounts();
        return view('financial-planning.create', ['accountingAccounts' => $accountingAccounts ?? []]);

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'valor' => 'required|numeric',
            'descripcion' => 'required|string|max:255',
            'fecha' => 'required|date',
            'cuentacontable_id' => 'nullable|integer',
        ]);

        $user = $request->user();
        $baseUrl = config('services.spring_financial.base_url');

        $payload = [
            'userId' => $user->id,
            'planName' => $validated['descripcion'], // o otro campo
            'description' => $validated['descripcion'],
            'projectedValue' => $validated['valor'],
            'projectedDate' => $validated['fecha'] . 'T00:00:00',
            'personalProject' => true
        ];

        $response = Http::acceptJson()
            ->asJson()
            ->post("{$baseUrl}/api/financial-planning", $payload);

        if ($response->successful()) {
            return redirect()->route('financial-planning.index')
                ->with('success', 'Registro creado exitosamente.');
        }

        return redirect()->back()
            ->with('error', 'Error al crear el registro: ' . $response->status())
            ->withInput();

    }


    public function update(Request $request, $planId)
    {
        $user = $request->user();
        $baseUrl = config('services.spring_financial.base_url');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'budget' => 'required|numeric|min:0',
        ]);

        $payload = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'startDate' => $validated['startDate'],
            'endDate' => $validated['endDate'],
            'budget' => $validated['budget'],
        ];

        $response = Http::acceptJson()
            ->asJson()
            ->put("{$baseUrl}/api/financial-planning/plan/{$planId}/user/{$user->id}", $payload);

        if ($response->successful()) {
            return redirect()->route('financial-planning.index')
                ->with('success', 'Financial plan updated successfully.');
        } else {
            return redirect()->route('financial-planning.index')
                ->with('error', 'Error updating financial plan: ' . $response->status());
        }
    }

    public function showUpdateForm($planId)
    {
        $user = auth::user();
        $baseUrl = config('services.spring_financial.base_url');

        $response = Http::get($baseUrl . '/api/financial-planning/plan/' . $planId . '/user/' . $user->id);

        /*
        if ($response->failed()) {
            return redirect()->route('financial-planning.index')->with('error', 'Error fetching data from financial planning service');
        }
        */

        $financialPlanning = $response->json();

        return view('financial-planning.update', [
            'financialPlanning' => $financialPlanning
        ]);
    }

    public function deletePlan($planId)
    {
        $user = auth::user();
        $baseUrl = config('services.spring_financial.base_url');

        $response = Http::delete($baseUrl . '/api/financial-planning/plan/' . $planId . '/user/' . $user->id);

        if ($response->failed()) {
            return redirect()->route('financial-planning.index')->with('error', 'Error deleting plan from financial planning service');
        }

        return redirect()->route('financial-planning.index')->with('success', 'Financial plan deleted successfully.');
    }
}
