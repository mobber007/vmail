<?php

namespace App\Http\Middleware;

use Closure;

class CheckApiKey
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
      if ($request->api_key != env('SMAIL_KEY')) {
            return response()->json([
              'data' => null,
              'error' => 'Invalid  API KEY',
              'servers_online' => 3
            ]);
        }
        return $next($request);
    }
}
