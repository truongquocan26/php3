@component('mail::message')
# Đặt lại mật khẩu

Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.

@component('mail::button', ['url' => route('account.auth.password.reset', ['token' => $token, 'email' => $email])])
Đặt lại mật khẩu
@endcomponent

Liên kết này sẽ hết hạn sau {{ $minutes }} phút.

Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.

Cảm ơn,<br>
{{ config('app.name') }}
@endcomponent
