<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeatureCreate extends FormRequest
{
    public function rules()
    {
        return [
            'name'=>'required|string|min:1'
        ];
    }
}
