<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Cek apakah role user adalah admin (Case Insensitive)
        if (strtolower(Auth::user()->role) !== 'admin') {
            abort(403, 'Akses Ditolak. Anda bukan Administrator.');
        }

        return $next($request);
    }
}