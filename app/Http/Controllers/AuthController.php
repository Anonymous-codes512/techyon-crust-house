<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
        $auth->password = Hash::make($req->password);
        $auth->save();
        
        return redirect()->route('viewLoginPage');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if($user->role === 'owner' ){
                session(['username' => $user->name]);
                return redirect()->route('dashboard');
            }
            else if($user->role === 'admin' ){    
                session(['username' => $user->name]);
                return redirect()->route('admindashboard');
            }
        } else {
            return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
        }
    }
}


