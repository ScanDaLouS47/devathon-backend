<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class UpdateBookingRequest extends FormRequest
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
            'reservationDate' => ['date', function ($attribute, $value, $fail) {
                if (strtotime($value) <= time()) {
                    $fail('The ' . $attribute . ' must be a date before the current date.');
                }
            }],
            'userId' => 'exists:users,id',
            'tableId' => 'exists:tables,id',
            'statusId' => 'exists:statuses,id'                
        ];
    }

    public function messages()
    {
        return [
            'unique' => 'Table with this number already exist',            
            'integer' => 'Any field has invalid format',
            'string' => 'Any field has invalid format'            
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
