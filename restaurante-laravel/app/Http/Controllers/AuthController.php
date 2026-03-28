<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // Verificar si el usuario existe y está activo
        if ($user && !$user->activo) {
            return back()->withErrors([
                'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirigir según el rol
            return $this->redirectBasedOnRole();
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Redirigir según el rol del usuario
     */
    private function redirectBasedOnRole()
    {
        $user = Auth::user();

        return match($user->rol) {
            'admin' => redirect()->route('admin.dashboard'),
            'recepcionista' => redirect()->route('pos.index'),
            'cocina' => redirect()->route('cocina.index'),
            default => redirect()->route('dashboard'),
        };
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Mostrar formulario de cambiar contraseña
     */
    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    /**
     * Cambiar contraseña
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_actual, $user->password)) {
            return back()->withErrors([
                'password_actual' => 'La contraseña actual es incorrecta.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
