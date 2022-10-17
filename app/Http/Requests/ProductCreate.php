<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreate extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'=>'required|string|min:1',
            'category_id'=>'required|integer|min:1',
            "features"=> "array",
            'features.*.id'=>'required_without:features.*.name|integer|min:1',
            'features.*.name'=>'required_without:features.*.id|string|min:1',
            'features.*.value'=>'required|string|min:1',
        ];
    }
}
