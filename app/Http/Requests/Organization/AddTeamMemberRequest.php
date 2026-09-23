<?php

namespace App\Http\Requests\Organization;

use App\Http\Requests\Concerns\ResolvesOrganization;
use Illuminate\Foundation\Http\FormRequest;

class AddTeamMemberRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }
}

