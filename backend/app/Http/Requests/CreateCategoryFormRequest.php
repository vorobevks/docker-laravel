<?php

namespace App\Http\Requests;

use App\DTO\CategoryDTO;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateCategoryFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'image' => 'required|file|mimes:jpg,bmp,png',
            'parent_id' => 'exists:categories,id,deleted_at,NULL|nullable'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Ошибки проверки',
            'data' => $validator->errors()
        ], 422));
    }

    public function getDto(): CategoryDTO
    {
        return new CategoryDTO($this->name, $this->image, $this->parent_id);
    }

}
