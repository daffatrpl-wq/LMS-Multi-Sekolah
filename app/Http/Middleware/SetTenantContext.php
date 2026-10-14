<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
   public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->role !== 'super_admin' && $user->school_id) {
            app()->instance('current_school_id', $user->school_id);
        }

        return $next($request);
    }
}
