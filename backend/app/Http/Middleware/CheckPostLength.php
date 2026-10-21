<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPostLength
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // \Log::info('Middlewareで書き込み.');
        return $next($request);

    }
}
