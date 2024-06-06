<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginIndex()
    {
        return view('Auth.Login');
    }

    public function registrationIndex()
    {
        return view('Auth.Registration');
    }

    public function register(Request $req)
    {
        $validateData = $req->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $auth = new User();
        $auth->name = $req->name;
        $auth->email = $req->email;
        $auth->role = $req->role;
        $auth->password = Hash::make($req->password);
        $auth->save();

        if ($req->has('role')) {
            return redirect()->route('viewStaffPage');
        } else {
            return redirect()->route('viewLoginPage');
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session(['user_id' => $user->id]);
            session(['username' => $user->name]);
            if ($user->role === 'owner') {
                return redirect()->route('dashboard');
            } else if ($user->role === 'admin') {
                return redirect()->route('admindashboard');
            } else if ($user->role === 'salesman') {
                return redirect()->route('salesman_dashboard', ['id' => $user->id]);
            }
        } else {
            return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
        }
    }

    public function logout()
    {
        session()->forget(['user_id', 'username']);
        return redirect()->route('viewLoginPage');
    }
}
