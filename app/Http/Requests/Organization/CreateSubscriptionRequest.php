<?php

namespace App\Http\Requests\Organization;

use App\Http\Requests\Concerns\ResolvesOrganization;
use Illuminate\Foundation\Http\FormRequest;

class CreateSubscriptionRequest extends FormRequest
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
            'plan_id' => ['required', 'exists:subscription_plans,id'],
            'is_trial' => ['sometimes', 'boolean'],
        ];
    }
}

