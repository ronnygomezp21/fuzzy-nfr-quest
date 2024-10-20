<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\GeneralResponse;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    use GeneralResponse;

    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return $this->generalResponse(null, 'El token ha expirado. Por favor, inicie sesión nuevamente.', 401);
        } catch (TokenInvalidException $e) {
            return $this->generalResponse(null, 'El token ingresado no es válido.', 401);
        } catch (JWTException $e) {
            return $this->generalResponse(null, 'No se ha enviado un token de autenticación.', 400);
        }
        return $next($request);
    }
}
