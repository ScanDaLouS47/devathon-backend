<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class CreateBookingRequest extends FormRequest
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
            'reservationDate' => ['required', 'date', function ($attribute, $value, $fail) {
                if (strtotime($value) <= time()) {
                    $fail('The ' . $attribute . ' must be a date before the current date.');
                }
            }],
            'userId' => 'required|exists:users,id',
            'tableId' => 'required|exists:tables,id',
            'statusId' => 'required|exists:statuses,id'                
        ];
    }

    public function messages()
    {
        return [            
            'required' => 'Required data is missing',            
            'date' => 'Any field has invalid format',
            'exists' => 'Any field has invalid format'            
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
