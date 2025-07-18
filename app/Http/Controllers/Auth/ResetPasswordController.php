<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = $password;
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? to_route('login')->with('success', 'Mật khẩu của bạn đã được đặt lại.')
            : back()->withErrors(['error_reset' => 'Mã thông báo đặt lại mật khẩu này không hợp lệ.']);
    }
}
