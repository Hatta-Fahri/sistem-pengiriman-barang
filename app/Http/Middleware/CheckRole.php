<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect('/login');
        }

        if (\Illuminate\Support\Facades\Auth::user()->role !== $role) {
            $userRole = \Illuminate\Support\Facades\Auth::user()->role;
            $intended = $userRole === 'admin' ? '/admin/dashboard' : '/courier/dashboard';
            
            return redirect($intended)->withErrors(['error' => 'Anda tidak memiliki izin mengakses halaman tersebut.']);
        }

        return $next($request);
    }
}
