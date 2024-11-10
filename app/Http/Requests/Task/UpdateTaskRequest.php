<?php

namespace App\Http\Requests\Task;

use Carbon\Carbon;
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
    public function prepareForValidation()
    {
        $dueDate = $this->input('due_date');

        if ($dueDate) {
            try {
                $dueDate = Carbon::createFromFormat('d-m-Y H:i', $dueDate);
            } catch (\Exception $e) {
                throw new HttpResponseException(response()->json([
                    'status' => 'error',
                    'message' => 'Invalid due_date format.',
                    'errors' => ['due_date' => 'The due_date must match the format d-m-Y H:i.']
                ]));
            }
        }




        $this->merge([
            'due_date' => $dueDate ? $dueDate->format('d-m-Y H:i') : null,
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
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'nullable|string|in:height,low,medium',
            'status' => 'nullable|string|in:Open,In_Progress,Completed,Blocked',
            'type' => 'nullable|string|in:Bug,Feature,Improvement',
            'due_date' => 'nullable|date|after:now',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'please make sure for the inputs  ',
            'errors' => $validator->errors(),

        ]));
    }


    public function attributes()
    {
        return [
            'title' => 'title',
            'description' => 'description',
            'priority' => 'priority',
            'due_date' => 'due_date',
            'assigned_to' => 'assigned_to',
            'type' => 'type',
            'status' => 'status',


        ];
    }
    public function messages()
    {
        return [
            'required' => ':attribute is required',
            'date' => 'The :attribute must be a valid date',
            'due_date.after' => 'The due date must be a date after today.',
            'assigned_to.exists' => 'The selected user does not exist.',
            'priority.in' => 'The priority must be one of the following values: low,medium, height',
            'status.in' => 'The status must be one of the following values:Open,In_Progress,Completed,Blocked',
            'type.in' => 'The type must be one of the following values:Bug,Feature,Improvement',
        ];
    }
}
