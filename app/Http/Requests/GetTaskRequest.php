<?php

namespace App\Http\Requests;

use App\Enums\PriorityEnum;
use App\Enums\StatusEnum;
use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Task::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'filter.status' => ['nullable', Rule::in(array_column(StatusEnum::cases(), 'value'))],
            'filter.priority' => ['nullable', Rule::in(array_column(PriorityEnum::cases(), 'value'))],
            'filter.due_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'order_direction' => ['nullable', Rule::in(['desc', 'asc'])]
        ];
    }
}
