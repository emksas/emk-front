<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 
use App\Services\DashboardServices; // Importamos el servicio de cálculos
use Carbon\Carbon; // Importamos Carbon para manejar el mes/año actual

class DashboardController extends Controller
{
    // Creamos el constructor para recibir el servicio que calcula los montos
    public function __construct(private DashboardServices $dashboardServices)
    {
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Capturamos el año y mes actuales para filtrar los gastos del mes en curso
        $year = $request->query('year', Carbon::now()->year);
        $month = $request->query('month', Carbon::now()->month);

        switch ($user->role) {
            case 'FAMILIAR':
                // 1. Traemos los usuarios de rol PERSONAL actuales para el formulario
                $usuariosPersonal = User::where('role', 'PERSONAL')->get(['id', 'name']);
                
                // 2. EJECUTAMOS EL SERVICIO: Trae las sumas reales de la base de datos
                $dashboardData = $this->dashboardServices->getDashboardData($year, $month);
                
                // 3. Le pasamos ambas variables a tu vista familiar.blade
                return view('dashboard.familiar', compact('usuariosPersonal', 'dashboardData'));

            case 'EMPRESARIAL':
                return view('dashboard.empresarial');

            case 'PERSONAL':
            default:
                // Al dashboard personal también le inyectamos los datos del mes actual
                $dashboardData = $this->dashboardServices->getDashboardData($year, $month);
                return view('dashboard', compact('dashboardData')); 
        }
    }
}