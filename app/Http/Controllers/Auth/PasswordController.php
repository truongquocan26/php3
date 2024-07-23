<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:3,60')->only('sendResetLinkEmail');
    }
    
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $account = User::where('email', $request->email)->first();

        if (!$account) {
            return back()->withErrors(['email' => 'Email không tồn tại.']);
        }

        $this->middleware('throttle:3,300')->only('sendResetLinkEmail');

        $token = Str::random(60);
        $expires = Carbon::now()->addMinutes(config('auth.passwords.users.expire'));

        DB::table('password_resets')->insert([
            'email' => $account->email,
            'token' => $token,
            'created_at' => now(),
            'expires_at' => $expires
        ]);

        $minutes = config('auth.passwords.users.expire');

        Mail::to($request->email)->send(new ResetPasswordMail($token, $minutes, $account->email));

        return back()->with(['status' => 'Link đặt lại mật khẩu đã được gửi đến email của bạn!']);
    }

    public function showResetForm($token, $email)
    {
        $cacheKey = 'password_changed_' . $email;
        if (Cache::has($cacheKey)) {
            return abort(419);
        }

        $reset = DB::table('password_resets')
            ->where('token', $token)
            ->first();

        if (!$reset) {
            return abort(419);
        }

        $tokenCreatedAt = Carbon::parse($reset->created_at);
        $now = Carbon::now();

        if ($tokenCreatedAt->diffInMinutes($now) > config('auth.passwords.users.expire')) {
            return abort(419);
        }

        return view('auth.passwords.reset', ['token' => $token, 'email' => $email]);
    }



    public function reset(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required'
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->withErrors(['password' => 'Đường dẫn đặt lại mật khẩu không hợp lệ!']);
        }

        $tokenCreatedAt = Carbon::parse($reset->created_at);
        $now = Carbon::now();

        // Kiểm tra xem token có hết hạn không
        if ($tokenCreatedAt->diffInMinutes($now) > config('auth.passwords.users.expire')) {
            return back()->withErrors(['email' => 'Link đặt lại mật khẩu đã hết hạn!']);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống!']);
        }

        // Kiểm tra xem người dùng đã đổi mật khẩu thành công chưa
        if ($request->session()->has('password_changed')) {
            // Nếu đã đổi mật khẩu thành công, từ chối yêu cầu reset tiếp theo
            return abort(419);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $cacheKey = 'password_changed_' . $user->email;
        $cacheMinutes = 2; 
        Cache::put($cacheKey, true, $cacheMinutes);

        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect('/')->with(['status' => 'Mật khẩu đã được đặt lại thành công!']);
    }
}
