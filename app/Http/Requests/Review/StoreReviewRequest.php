<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isReviewer() || $this->user()?->isAdmin();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'recommendation' => ['required', 'string', 'in:approved,revision_required,rejected'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
