<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\ApiResponseService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

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
     * Prepare the data for validation.
     * This method is called before validation starts to clean or normalize inputs.
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'title' => $this->title ? ucwords(trim($this->title)) : null,
            'priority' => $this->priority ? strtolower(trim($this->priority)) : null,
            'status' => $this->status ? strtolower(trim($this->status)) : null,
            'due_date' => $this->due_date ? date('Y-m-d', strtotime($this->due_date)) : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|required|string|max:255|min:5',
            'description' => 'nullable|required|string',
            'priority' => 'nullable|required|in:low,medium,high',
            'due_date' => 'nullable|required|date|after_or_equal:today',
            'status' => 'nullable|required|in:new,in_progress,completed,failed',
            'assigned_to' => 'nullable|exists:users,id',
            'project_id' => 'nullable|required|exists:projects,id',
            'hours' => 'nullable|required|integer|min:1',
        ];
    }

    /**
     * Define custom attribute names for validation errors.
     * 
     * @return array
     */
    public function attributes(): array
    {
        return [
            'title' => 'task title',
            'description' => 'task description',
            'priority' => 'task priority',
            'due_date' => 'task due date',
            'status' => 'task status',
            'assigned_to' => 'assigned user',
            'project_id' => 'project',
            'hours' => 'estimated hours',
        ];
    }

    /**
     * Define custom error messages for validation failures.
     * 
     * @return array
     */
    public function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'string' => 'The :attribute must be a string.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'min' => 'The :attribute must be at least :min characters.',
            'in' => 'The selected :attribute is invalid.',
            'date' => 'The :attribute is not a valid date.',
            'exists' => 'The selected :attribute does not exist.',
            'integer' => 'The :attribute must be an integer.',
            'nullable' => 'The :attribute may be null.',
            'after_or_equal' => 'The :attribute must be a date that is today or a future date.',
        ];
    }

    /**
     * Handle validation errors and throw an exception.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator The validation instance.
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(
            ApiResponseService::error($errors, 'A server error has occurred', 500)
        );
    }
}
