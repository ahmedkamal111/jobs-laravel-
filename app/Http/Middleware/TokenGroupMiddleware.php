<?php

namespace App\Http\Middleware;

use Closure;
use App\Encoder;
use App\TokenDecoded;
use App\TokenEncoded;
use App\JWT;
use Exceptions\SigningFailedException;
use Exceptions\IntegrityViolationException;
use Exceptions\UnsupportedAlgorithmException;
use Exceptions\EmptyTokenException;
class TokenGroupMiddleware
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
      try{
      $tokenEncoded = new TokenEncoded($request->header('Authorization'));
      }
      catch(EmptyTokenException $e){
      }
      $publicKey = file_get_contents('/home/tanta/joblaravelTest.tanta.club/rsa.public');
      try {
          $tokenEncoded->validate($publicKey, JWT::ALGORITHM_RS256);
      } catch (IntegrityViolationException $e) {
      } catch (Exception $e) {
      }
      $tokenDecoded = $tokenEncoded->decode();
      $payload = $tokenDecoded->getPayload();
        if(time()<$payload['iat']){
            return $next($request);
        }
        return response()->json(['status_code' => '404']);
    }
}
