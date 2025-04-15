<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanVerifyPelanggan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
{
    $pelanggan = $request->route('pelanggan');
    
    if (!auth()->user()->can('verifikasi', $pelanggan)) {
        abort(403, 'Unauthorized action.');
    }
    
    return $next($request);
}
}
