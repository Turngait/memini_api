<?php

namespace App\Http\Middleware;

use App\Models\UserToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserID
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->headers->get('user_token')) return response()->json(["status" => false, "id" => null, "token" => "", "msg" => "Wrong user token"], 403);
        
        $user_id = UserToken::where('token', '=', $request->headers->get('user_token'))->value('user_id');
        if(!$user_id) return response()->json(["status" => false, "id" => null, "token" => "", "msg" => "Wrong user token"], 403);
        $response = $next($request);
        $response->headers->set('user_id', $user_id);

        return $response;
    }
}
