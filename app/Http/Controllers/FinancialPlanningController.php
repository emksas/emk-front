<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FinancialPlanningController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        $baseUrl = config('services.spring_financial.base_url');

        $response = Http::get($baseUrl . '/api/financial-planning/user/' . $user->id);

        $financialPlannings = $response->json();

        if ($response->failed()) {
            return view('financial-planning.index', [
                'error' => 'Error fetching data from financial planning service',
                'spring_status' => $response->status(),
            ]);
        }

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

    public function showCreateForm()
    {
        return view('financial-planning.create');
    }


    public function showUpdateForm($planId)
    {
        $user = auth()->user();
        $baseUrl = config('services.spring_financial.base_url');

        $response = Http::get($baseUrl . '/api/financial-planning/plan/' . $planId . '/user/' . $user->id);

        if ($response->failed()) {
            return redirect()->route('financial-planning.index')->with('error', 'Error fetching data from financial planning service');
        }

        $financialPlanning = $response->json();

        return view('financial-planning.update', [
            'financialPlanning' => $financialPlanning
        ]);
    }

    public function deletePlan($planId)
    {
        $user = auth()->user();
        $baseUrl = config('services.spring_financial.base_url');

        $response = Http::delete($baseUrl . '/api/financial-planning/plan/' . $planId . '/user/' . $user->id);

        if ($response->failed()) {
            return redirect()->route('financial-planning.index')->with('error', 'Error deleting plan from financial planning service');
        }

        return redirect()->route('financial-planning.index')->with('success', 'Financial plan deleted successfully.');
    }
}
