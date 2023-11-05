<?php

namespace App\Http\Middleware;

use Closure;

class CheckPostLength
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // \Log::info('Middlewareで書き込み.');
        return $next($request);

    }
}
