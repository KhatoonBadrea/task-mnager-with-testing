<?php

namespace App\Http\Requests\Task;

use App\Rules\AssigneToRule;
use Illuminate\Foundation\Http\FormRequest;

class updateAssignedRequest extends FormRequest
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
            'assigned_to' => 'nullable', 'integer', 'exists:users,id',
        ];
    }
}
