<?php 
namespace App\ViewModels\Responses;

use Spatie\ViewModels\ViewModel;

class JWT extends ViewModel
{
    public string $accessToken;
    public string $refreshToken;
}