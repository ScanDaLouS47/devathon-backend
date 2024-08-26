<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\ImageFile;
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
        $user = auth()->user();

        return [
            'name' => "required|string|regex:/^[A-Za-zÀ-ÖØ-öø-ÿĀ-ž' ]{3,50}$/",
            'lName' => "required|string|regex:/^[A-Za-zÀ-ÖØ-öø-ÿĀ-ž' ]{3,50}$/",
            'email' => 'required|email',
            'phone' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/',
            'file' => ['nullable', ImageFile::image()->max(3 * 1024)]
        ];
    }

    public function messages()
    {
        return [
            'unique' => 'Some data belongs to other users',
            'required' => 'Required data is missing',
            'regex' => 'Any field has invalid format',
            'email' => 'Email has invalid format'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json([
                'ok' => false,
                'data' => null,
                'msg' => $errors
            ])
        );
    }
}
