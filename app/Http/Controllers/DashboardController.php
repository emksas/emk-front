<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Asumiendo que usarás esto para listar usuarios en el rol familiar

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'FAMILIAR':
                // Para el rol familiar, necesitamos los usuarios de rol PERSONAL actuales
                $usuariosPersonal = User::where('role', 'PERSONAL')->get(['id', 'name']);
                
                return view('dashboard.familiar', compact('usuariosPersonal'));

            case 'EMPRESARIAL':
                // Aquí podrías cargar datos iniciales de ingresos/egresos si ya existen
                return view('dashboard.empresarial');

            case 'PERSONAL':
            default:
                // Retorna la vista base que ya tienes configurada
                return view('dashboard'); 
        }
    }
}