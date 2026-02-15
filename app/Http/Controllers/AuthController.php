<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Show login form
    public function showLogin($role)
    {
        return view('auth.login', compact('role'));
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            if (Auth::user()->role !== $request->role) {
                Auth::logout();
                return back()->withErrors(['email' => 'Role tidak sesuai']);
            }

            if ($request->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Login gagal']);
    }

    // Show register
    public function showRegister($role)
    {
        return view('auth.register', compact('role'));
    }

    // Handle register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        // AUTO LOGIN
        Auth::login($user);

        // Redirect sesuai role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
