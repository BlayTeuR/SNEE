<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsTechnicien
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifie que l'utilisateur est authentifié et a le rôle "technicien"
        if ($request->user() && $request->user()->isTechnicien()) {
            return $next($request);
        }

        // Sinon, renvoie une erreur 403 ou redirige
        abort(403, 'Accès réservé aux techniciens.');
    }
}
