<?php

namespace App\Http\Requests\Organization;

use App\Http\Requests\Concerns\ResolvesOrganization;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    use ResolvesOrganization;

    public function authorize(): bool
    {
        $organization = $this->organizationFromRoute();

        return $organization && $this->user()->can('update', $organization);
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'timezone' => ['sometimes', 'required', 'string', 'timezone'],
            'locale' => ['sometimes', 'required', 'string', 'max:10'],
            'country_code' => ['sometimes', 'nullable', 'string', 'max:2'],
            'settings' => ['sometimes', 'array'],
            'settings.*' => ['nullable'],
        ];
    }
}

