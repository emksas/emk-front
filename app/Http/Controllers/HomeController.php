<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\services\DashboardServices;
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

        if ($user->isAdmin()) {
            return view('dashboard', $this->userTypeService->getUserManagementData() + [
                'isAdminDashboard' => true,
            ]);
        }

        $year = $request->query('year', Carbon::now()->year);
        $month = $request->query('month', Carbon::now()->month);

        // CORRECCIÓN CON EL MÉTODO REAL: Usamos getDashboardData con sus respectivos parámetros
        $dashboardData = $this->dashboardServices->getDashboardData($year, $month, $user->id);

        return view('dashboard', compact('dashboardData'));

        /*
        switch ($typeUser['nombre']) {
            case 'Family Role':
                $usuariosPersonal = User::where('role', 'PERSONAL')->get(['id', 'name']);

                // ACTUALIZACIÓN: Cargamos la información de gastos/totales para las cards del Familiar
                $dashboardData = $this->dashboardServices->getDashboardData($year, $month);

                return view('dashboard.familiar', compact('usuariosPersonal', 'dashboardData'));

            case 'Business Role':
                return view('dashboard.empresarial');

            case 'Individual Role':
            default:
                // Usamos getDashboardData con sus respectivos parámetros
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
