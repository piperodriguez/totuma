<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Importación necesaria del modelo
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Muestra el panel principal del usuario con sus estadísticas de puntos.
     */
    public function index()
    {
        // Obtenemos al usuario autenticado
        $user = Auth::user();
        $metaPuntos = 500;

        return view('dashboard', [
            'puntosTotales' => $user->puntos, // Saldo histórico
            'ultimoCargue'  => $user->ultimo_cargue_puntos, // Valor del último proceso
            'metaPuntos'    => $metaPuntos,
            'porcentaje'    => min(100, round(($user->puntos / $metaPuntos) * 100))
        ]);
    }
}
