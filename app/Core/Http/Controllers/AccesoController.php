<?php

namespace App\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AccesoController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/Login');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['dni' => ['required', 'regex:/^[0-9]{8}$/'], 'password' => ['required', 'string']]);
        $personaActiva = DB::table('personas')->where('dni', $data['dni'])->whereNull('deleted_at')->exists();
        if (! $personaActiva || ! Auth::attempt([...$data, 'activo' => true])) {
            throw ValidationException::withMessages(['dni' => 'Credenciales inválidas.']);
        }
        $request->session()->regenerate();
        $request->session()->forget(['perfil_id', 'periodo_id']);

        return redirect()->route('contexto.edit');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
