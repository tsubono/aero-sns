<?php

namespace App\Http\Middleware;

use App\Enums\UserStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('web')->user();

        if ($user && $user->status === UserStatus::Suspended) {
            Auth::guard('web')->logout();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['email' => 'アカウントが停止されています。管理者にお問い合わせください。']);
        }

        return $next($request);
    }
}
