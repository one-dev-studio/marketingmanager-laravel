<?php

namespace App\Http\Requests\Organization;

use App\Http\Requests\Concerns\ResolvesOrganization;
use Illuminate\Foundation\Http\FormRequest;

class ConnectStorageSourceRequest extends FormRequest
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
            'provider' => ['required', 'string', 'in:s3,google_drive,dropbox'],
            'name' => ['sometimes', 'string', 'max:255'],
            'access_token' => ['required', 'string'],
            'refresh_token' => ['sometimes', 'nullable', 'string'],
            'settings' => ['sometimes', 'array'],
        ];
    }
}

