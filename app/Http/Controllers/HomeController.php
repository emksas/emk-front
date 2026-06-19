<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\DashboardServices;
use App\services\UserTypeService;
use Carbon\Carbon; // Importamos Carbon para sacar el año/mes actual si no vienen en la URL

class HomeController extends Controller
{
    public function __construct(
        private DashboardServices $dashboardServices,
        private UserTypeService $userTypeService
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $typeUser =  $this->userTypeService->getUserTypeById($user->role);

        $year = $request->query('year', Carbon::now()->year);
        $month = $request->query('month', Carbon::now()->month);

        // CORRECCIÓN CON EL MÉTODO REAL: Usamos getDashboardData con sus respectivos parámetros
        $dashboardData = $this->dashboardServices->getDashboardData($year, $month);

        return view('dashboard', compact('dashboardData'));

        /*
        switch ($typeUser['nombre']) {
            case 'Family Role':
                $usuariosPersonal = User::where('role', 'PERSONAL')->get(['id', 'name']);
                return view('dashboard.familiar', compact('usuariosPersonal'));

            case 'Business Role':
                return view('dashboard.empresarial');

            case 'Individual Role':
            default:
                // Capturamos el año y mes del request, o usamos los actuales por defecto
                $year = $request->query('year', Carbon::now()->year);
                $month = $request->query('month', Carbon::now()->month);

                // CORRECCIÓN CON EL MÉTODO REAL: Usamos getDashboardData con sus respectivos parámetros
                $dashboardData = $this->dashboardServices->getDashboardData($year, $month);

                return view('dashboard', compact('dashboardData'));
        }
                */
    }

    public function years()
    {
        $years = $this->dashboardServices->yearsAvailables();
        return response()->json($years);
    }

    public function months(Request $request)
    {
        $year = $request->query('year');
        $months = $this->dashboardServices->months($year);
        return response()->json($months);
    }

    public function api()
    {
        $year = request()->query('year');
        $month = request()->query('month');

        $dashboardData = $this->dashboardServices->getDashboardData($year, $month);
        return response()->json($dashboardData);
    }
}
