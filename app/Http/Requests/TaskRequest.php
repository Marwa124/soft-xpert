<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use App\Http\Resources\ApiResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskRequest extends FormRequest
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
        $id = $this->task ? $this->task->id : '';

        return [
            'title' => [
                'required','unique:tasks,title',
                'string',
                'max:255'
            ],
            'description' => [
                'required',
                'max:2048',
                'string'
            ],
            'assignee_id' => [
                'required',
                'exists:users,id'
            ],
            'related_task_id' => [
                'nullable',
                'exists:tasks,id'
            ],
            'due_date' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'status' => [
                'nullable',
                'in:'.implode(',', TaskStatus::STATUS)
            ],
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(response()->json(new ApiResource(null, 422, $errors), 422));
    }
}
