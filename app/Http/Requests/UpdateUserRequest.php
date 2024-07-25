<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\User;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'lName' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'phone' => 'required|string|min:7|max:15|unique:users,phone,' . $this->user->id
        ];
    }

    public function messages()
    {
        return [
            'unique' => 'Some data belongs to other users',
            'required' => 'Required data is missing',
            'min' => 'Any field has invalid format',
            'max' => 'Any field has invalid format',
            'email' => 'Email has invalid format'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $errors = array_values($errors);
        
        throw new HttpResponseException(
            response()->json([
                'ok' => false,
                'data' => null,
                'msg' => $errors[0]
            ])
        );
    }
}
