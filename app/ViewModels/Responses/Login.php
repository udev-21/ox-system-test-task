<?php

namespace App\ViewModels\Responses;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class Login extends Base
{
    public Authenticatable $user;
    public JWT $accessToken;
    public JWT $refreshToken;

    function __construct(Authenticatable $user, JWT $token, JWT $refreshToken)
    {
        $this->user = $user;
        $this->accessToken = $token;
        $this->refreshToken = $refreshToken;
    }
}