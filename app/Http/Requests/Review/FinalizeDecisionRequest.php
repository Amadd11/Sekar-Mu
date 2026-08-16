<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class FinalizeDecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'decision' => ['required', 'string', 'in:approved,rejected,revision_required'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
