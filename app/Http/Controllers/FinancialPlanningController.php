<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FinancialPlanningController extends Controller
{
    // Este es el que ya teníamos tipo API (opcional)
    public function index(Request $request)
    {
        $user = $request->user();
        $baseUrl = config('services.spring_financial.base_url');

        $response = Http::get($baseUrl . '/financial-planning/' . $user->id);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Error fetching data from financial planning service',
                'spring_status' => $response->status(),
            ], 500);
        }

        return response()->json($response->json(), 200);
    }

    // 🔹 Este es el nuevo: devuelve una VISTA Blade
    public function viewPage(Request $request)
    {
        $user = $request->user();
        $baseUrl = config('services.spring_financial.base_url');

        $response = Http::get($baseUrl . '/financial-planning/status' );

        if ($response->failed()) {
            return view('financial-planning.index', [
                'error' => 'Error fetching data from financial planning service',
                'spring_status' => $response->status(),
            ]);
        }

        return view('financial-planning.index', [
            'response' => $response->json()
        ]);

    }
}
