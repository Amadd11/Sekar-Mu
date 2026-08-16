<?php

namespace App\Http\Requests\SelfAssessment;

use Illuminate\Foundation\Http\FormRequest;

class SaveAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'assessment_item_id' => ['required', 'exists:assessment_items,id'],
            'score' => ['nullable', 'string', 'in:A,B,C'],
            'comment' => ['nullable', 'string'],
            'evidence' => ['nullable', 'string'],
        ];
    }
}
