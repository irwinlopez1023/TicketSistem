<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function start(User $user)
    {
        // Solo admins pueden hacer esto
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }

        // No te puedes suplantar a ti mismo
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes suplantarte a ti mismo.');
        }

        // Guardar el ID del admin original en la sesión
        session()->put('impersonated_by', Auth::id());

        // Iniciar sesión como el usuario objetivo
        Auth::login($user);

        return redirect()->route('user.tickets.index')->with('success', "Has iniciado sesión como {$user->name}");
    }

    public function stop()
    {
        // Verificar si hay una sesión de suplantación activa
        if (!session()->has('impersonated_by')) {
            abort(403);
        }

        // Recuperar el ID del admin original
        $adminId = session()->pull('impersonated_by');

        // Iniciar sesión de nuevo como el admin
        Auth::loginUsingId($adminId);

        return redirect()->route('admin.users.index')->with('success', 'Bienvenido de vuelta, Admin.');
    }
}
