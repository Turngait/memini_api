<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAPIToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $api_key = env('API_KEY') | '';
        if($api_key === $request->headers->get('token')) return $next($request);
        return response()->json(["status" => false, "id" => null, "token" => "", "msg" => "Wrong api key"], 403);
    }
}
