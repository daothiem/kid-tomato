<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function getLogin(){
        return view('auth.login');
    }
    public function postLogin(Request $request){
        $input = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];
        if (Auth::attempt($input,true)) {
            return Redirect::route('admin.index');
        }

        return Redirect::route('admin.login')->with('error', 'Đăng nhập không thành công');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::route('admin.login');
    }
}
