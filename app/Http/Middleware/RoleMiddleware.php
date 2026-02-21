<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Jika role user tidak sesuai dengan role yang diminta di route
        if ($request->user()->role !== $role) {
            // Arahkan kembali ke dashboard masing-masing agar tidak bisa ngintip
            if ($request->user()->role === 'admin') {
                return redirect('/admin/dashboard');
            }
            return redirect('/pelanggan/dashboard');
        }

        return $next($request);
    }
}