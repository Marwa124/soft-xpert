<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use App\Http\Resources\ApiResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FilterRequest extends FormRequest
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
            'assignee_id' => [
                'nullable',
                'exists:users,id'
            ],
            'due_date' => [
                'nullable',
                'date',
            ],
            'status' => [
                'nullable',
                'in:'.implode(',', TaskStatus::STATUS)
            ],
            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100'
            ],
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(response()->json(new ApiResource(null, 422, $errors), 422));
    }
}
