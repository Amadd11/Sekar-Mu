<?php

namespace App\Http\Requests\ResearchProtocol;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResearchProtocolRequest extends FormRequest
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
            'protocol_number' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'principal_investigator' => ['required', 'string', 'max:255'],
            'submission_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'max:50'],
        ];
    }
}
