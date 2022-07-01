<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth;

        $checkToken = $jwtAuth->checkToken($token);

        if($checkToken){

            return $next($request);


        }
        else{
            $data = array(
                'status'  => 'error',
                'code'    => '404',
                'message' => 'EL usuario no esta autenticado Middleware'
            );
            return response()->json($data,$data['code']);
        }

        
    }
}
