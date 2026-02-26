<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /*
    |-----------------------------
    | CUSTOMER LOGIN
    |-----------------------------
    */

    public function showCustomerLogin()
    {
        return view('auth.customer-login');
    }

    public function loginCustomer(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            if (Auth::user()->role !== 'customer') {
                Auth::logout();

                return back()->withErrors(['email' => 'Akses ditolak']);
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Login gagal']);
    }

    /*
    |-----------------------------
    | ADMIN LOGIN
    |-----------------------------
    */

    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function loginAdmin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            if (Auth::user()->role !== 'admin') {
                Auth::logout();

                return back()->withErrors(['email' => 'Bukan akun admin']);
            }

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Login gagal']);
    }

    /*
    |-----------------------------
    | REGISTER (CUSTOMER ONLY)
    |-----------------------------
    */

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => 'customer',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('customer.login');
    }
}
