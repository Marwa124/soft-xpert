<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use App\Http\Resources\ApiResource;
use App\Rules\UpdateTaskRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTaskRequest extends FormRequest
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
                'nullable','unique:tasks,title,'.$id,
                'string',
                'max:255'
            ],
            'description' => [
                'nullable',
                'max:2048',
                'string'
            ],
            'assignee_id' => [
                'nullable',
                'exists:users,id'
            ],
            'related_task_id' => [
                'nullable',
                'exists:tasks,id',
                'not_in:' . $id,
            ],
            'due_date' => [
                'nullable',
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
