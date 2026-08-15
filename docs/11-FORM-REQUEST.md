# Form Request
Form Requests own validation and request authorization.

```php
class StoreResearchProtocolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('application'));
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'protocol_number' => ['required', 'string', 'max:100'],
            'principal_investigator' => ['required', 'string', 'max:255'],
        ];
    }
}
```

Validate all untrusted input server-side.
