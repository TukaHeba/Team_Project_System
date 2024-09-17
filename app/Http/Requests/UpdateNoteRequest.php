<?php

namespace App\Http\Requests;

use App\Models\Task;
use App\Models\User;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * Admin can update notes in any task
     * Tester can update notes only if the task assigend to him
     * Others can not add notes
     * @return bool
     */
    public function authorize(): bool
    {
        $user = User::find(Auth::id());
        $taskId = $this->route('taskId');

        $task = Task::find($taskId);

        if ($user->isAdmin()) {
            return true;
        }

        #FIXME this part always gives me : This action is unauthorized
        // even if the user is really has tester role and assigned to this task
        if ($user->hasRoleInTask($taskId, 'tester')) {
            return true;
        }

        return false;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'written_by' => Auth::id(),
            'task_id' => $this->route('taskId'),
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
            'note' => 'nullable|string|max:1000|min:10',
            'task_id' => 'nullable|exists:tasks,id',
            'written_by' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Get custom attribute names.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'note' => 'note content',
            'task_id' => 'task',
            'user_id' => 'writer user',
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'min' => 'The :attribute must be at least :min characters.',
            'exists' => 'The selected :attribute does not exist.',
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
