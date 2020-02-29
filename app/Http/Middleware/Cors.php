<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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

        // ALLOW OPTIONS METHOD
        header("Access-Control-Allow-Origin: *");
        $headers = [
            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE,PATCH',
            'Access-Control-Allow-Headers'=> 'Content-Type, X-Auth-Token, Origin,X-Requested-With,Accept,ip,Authorization,Range,Content-Range',
            'Access-Control-Expose-Headers' => 'Accept-Ranges, Content-Encoding, Content-Length, Content-Range,Authorization'
        ];
        if($request->getMethod() == "OPTIONS") {
            // The client-side application can set only headers allowed in Access-Control-Allow-Headers
            return Response::make('OK', 200, $headers);
        }

        $response = $next($request);
        foreach($headers as $key => $value)
            $response->header($key, $value);
        return $response;
    }
}
