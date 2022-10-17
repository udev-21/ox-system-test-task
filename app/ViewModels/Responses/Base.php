<?php

namespace App\ViewModels\Responses;

use Spatie\ViewModels\ViewModel;

abstract class Base extends ViewModel
{
    public $errorMsg = null;
}