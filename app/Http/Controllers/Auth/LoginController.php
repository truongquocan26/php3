<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    const PATH_VIEW = 'auth.account.';
    public function showLogin()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    public function login(Request $request)
    {
        $data = $request->has('user_name') ?
            $request->validate(['user_name' => 'required', 'password' => 'required']) :
            $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return redirect()->back()->withErrors([
            'error' => 'Tài khoản hoặc mật khẩu không chính xác'
        ]);
    }

    public function logout()
    {
        Auth::logout();
        \request()->session()->invalidate();
        return redirect('/');
    }
}
