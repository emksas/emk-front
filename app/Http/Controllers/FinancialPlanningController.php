<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FinancialPlanningController extends Controller
{

    public function viewPage(Request $request)
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
}
