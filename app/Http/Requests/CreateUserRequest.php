<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Role;


class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'lName' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone|min:7|max:15',
            'sup_id' => 'required|string|unique:users,sup_id|max:70'
        ];
    }

    public function messages()
    {
        return [
            'unique' => 'User already exist',
            'required' => 'Required data is missing',
            'min' => 'Any field has invalid format',
            'max' => 'Any field has invalid format',
            'email' => 'Email has invalid format'
        ];
    }

    public function getDefaultRoleId(): int
    {
        return Role::where('type', 'user')->firstOrFail()->id;
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
