<?php

namespace App\Api\Middleware;

use App\Exceptions\ForbiddenException;
use Closure;
use Illuminate\Support\Facades\Auth;

class UserContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $user_id = ($request->has('user_id')) ?
            $request->get('user_id') :
            Auth::user()->getAuthIdentifier();

        if ($user_id != Auth::user()->getAuthIdentifier() && !auth()->user()->hasRole('sa')) {
            throw new ForbiddenException(403);
        }

        $request->merge(['user_id' => $user_id]);

        return $next($request);
    }
}
