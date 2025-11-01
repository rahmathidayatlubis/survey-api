<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== $role) {
            // Jika bukan role yang sesuai, kembalikan ke halaman dashboard-nya sendiri
            if (Auth::user()->role === 'admin') {
                return redirect('/admin/dashboard')->with('error', 'Akses ditolak.');
            }

            if (Auth::user()->role === 'mahasiswa') {
                return redirect('/mahasiswa/dashboard')->with('error', 'Akses ditolak.');
            }

            // fallback default
            return redirect('/')->with('error', 'Akses tidak diizinkan.');
        }

        return $next($request);
    }
}
