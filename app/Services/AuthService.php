<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\ViewModels\Responses\Register;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthService
{
    public function __construct(protected JWTService $JWTService) {
    }
    public function login(LoginRequest $request)  {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            $id = Auth::user()->id;
            return $this->JWTService->issue($id);
        }
        throw new BadRequestHttpException();
    }

    public function register(RegisterRequest $request) {
        $validated = $request->validated();
        $user = User::create(array_merge(
            $validated,
            ['password' => bcrypt($request->password)]
        ));
        return response()->json(['success'=>true, 'user'=>$user, 'jwt'=> $this->JWTService->issue($user->id)], 201);
    }

    public function refresh() {
        return $this->JWTService->issue(auth()->user()->id);
    }
}