<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetArticlesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'source' => 'nullable|string|max:100',
            'author' => 'nullable|string|max:100',
            'date' => 'nullable|date',
        ];
    }
}