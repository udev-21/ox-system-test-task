<?php
namespace App\ViewModels\Responses;

class Register extends Base 
{
    public $user;
    public JWT $accessToken;
    public JWT $refreshToken;

    function __construct($user, JWT $accessToken, JWT $refreshToken)
    {
        $this->user = $user;
        $this->accessToken = $accessToken;
        $this->refreshToken = $accessToken;
        
    }
}