<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        return view('auth');
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'family' => 'required',
            'username' => ['required', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required'
        ]);
        $user = User::create([
            'name' => $request->name,
            'family' => $request->family,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password
        ]);
        Auth::login($user);
        return redirect('/');

    }

    public function login(Request $request){
        $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)){
            if($user->is_admin){
                Auth::login($user);
                Auth::guard('admin')->login($user);
                $request->session()->regenerate();
                session([
                    'user.name' => $user->name,
                    'user.family' => $user->family,
                    'user.username' => $user->username
                ]);
                return redirect('/admin');                
            }else{
                Auth::login($user);
                $request->session()->regenerate();
                return redirect('/');
            }
        }else{        
            return back()->withErrors(['email'=> 'email or password invalid...!']);
        }
    }
    public function logout(){
        Auth::logout();
        return back();
    }
}
