<?php

namespace App\Http\Middleware;

use Closure;

class corsMiddleware
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

//        header("Access-Control-Allow-Origin: *");
//        header( 'Access-Control-Allow-Headers: Authorization, Content-Type, X-Auth-Token, Origin,x-token,AdminToken' );
//        header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
//        header('Access-Control-Allow-Credentials: true');

        // ALLOW OPTIONS METHOD
//        $headers = [
//            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
//            'Access-Control-Allow-Headers'=> 'Content-Type, X-Auth-Token, Origin,x-token,AdminToken,Authorization'
//
//        ];


//        header("Access-Control-Allow-Origin: *");
//        header( 'Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin,x-token,AdminToken,Time,Sha,Cookie,funmore' );
//        header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
//        header('Access-Control-Allow-Credentials: true');
        $headers = [
            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'=> 'Content-Type, X-Auth-Token, Origin,x-token,AdminToken,Time,Sha,Cookie,funmore',
            'Access-Control-Allow-Origin' =>'*',
            'Access-Control-Allow-Credentials'=>true

        ];

//        header("Access-Control-Allow-Origin: *");
//        header( 'Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin,x-token,AdminToken,Time,Sha,Cookie,funmore' );
//        header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
//        header('Access-Control-Allow-Credentials: true');
        if($request->getMethod() == "OPTIONS") {
            // The client-side application can set only headers allowed in Access-Control-Allow-Headers
            return Response::make('OK', 200, $headers);
        }
//        $response = $next($request);
//        foreach($headers as $key => $value)
//            $request->header($key, $value);
//        $response = $next($request);
        //return $next($request);
//        return $response;

        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin,x-token,AdminToken,Time,Sha,Cookie,funmore')
            ->header('Access-Control-Allow-Credentials',true);

    }
}
