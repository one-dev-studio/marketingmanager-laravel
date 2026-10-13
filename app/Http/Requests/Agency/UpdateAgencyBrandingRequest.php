<?php

declare(strict_types=1);

namespace App\Http\Requests\Agency;

use App\Support\Agency\AgencyPortalBrandingKeys;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAgencyBrandingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $hexColor = ['sometimes', 'nullable', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/'];

        return [
            'primary_color' => $hexColor,
            'secondary_color' => $hexColor,
            'logo' => 'sometimes|nullable|string|max:255',
            'favicon' => 'sometimes|nullable|string|max:255',
            AgencyPortalBrandingKeys::ENABLED => ['sometimes', 'boolean'],
            AgencyPortalBrandingKeys::TITLE => ['sometimes', 'nullable', 'string', 'max:120'],
            AgencyPortalBrandingKeys::WELCOME_TEXT => ['sometimes', 'nullable', 'string', 'max:2000'],
            AgencyPortalBrandingKeys::LOGO_URL => ['sometimes', 'nullable', 'string', 'max:2048', 'url'],
            AgencyPortalBrandingKeys::PRIMARY_COLOR => $hexColor,
            AgencyPortalBrandingKeys::SECONDARY_COLOR => $hexColor,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            AgencyPortalBrandingKeys::PRIMARY_COLOR.'.regex' => 'Portal primary color must be a hex value like #1a2b3c.',
            AgencyPortalBrandingKeys::SECONDARY_COLOR.'.regex' => 'Portal secondary color must be a hex value like #1a2b3c.',
            AgencyPortalBrandingKeys::LOGO_URL.'.url' => 'Portal logo must be a valid URL.',
        ];
    }
}
