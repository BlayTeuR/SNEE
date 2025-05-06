<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 1) si l'utilisateur n'est pas connecté, on le redirige vers le login
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // 2) si l'utilisateur est connecté mais pas admin, on renvoie une 403
        if (! $request->user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        // 3) sinon, tout est OK
        return $next($request);
    }
}
