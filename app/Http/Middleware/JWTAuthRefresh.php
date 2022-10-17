<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\JWTService;
use Closure;
use Illuminate\Http\Request;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class JWTAuthRefresh
{
    public function __construct(protected JWTService $JWTService) 
    {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authHeader = explode('Bearer ', $request->header('Authorization'), 2);
        if(count($authHeader) === 2) {
            $token = $authHeader[1];
            if($jwtToken = $this->JWTService->verifyRefresh($token)){
                $userID = intval($jwtToken->claims()->get('iss'));
                auth()->login(User::find($userID));
                return $next($request);
            }else {
                throw new BadRequestHttpException('token expired or not exists');
            }
        }else {
            throw new AccessDeniedHttpException();
        }
    }
}
