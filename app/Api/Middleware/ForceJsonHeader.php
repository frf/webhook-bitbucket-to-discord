<?php

namespace App\Api\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class ForceJsonHeader extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
