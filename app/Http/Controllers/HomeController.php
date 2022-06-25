<?php

namespace App\Http\Controllers;

use App\Helpers\Commons;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        $data = Commons::getMainDataArray();
        return view('home', $data);
    }


    public function login()
    {
        $user = Auth::user();
        if ($user == null)
            return view('auth.login');
        else return redirect('home');
    }

    public function submitLogin(Request $request)
    {
        $request->validate([
            "username"  =>  "required",
            "password"  =>  "required",
        ]);
        $user = User::login($request->username, $request->password);
        if ($user) {
            return redirect('home');
        }
        return redirect('login')->withErrors(["account"   =>  "Invalid Account"]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }
}
