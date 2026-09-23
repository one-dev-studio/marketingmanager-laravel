<?php

namespace App\Http\Requests\Concerns;

use App\Models\Organization;

trait ResolvesOrganization
{
    protected function organizationFromRoute(): ?Organization
    {
        $param = $this->route('organization') ?? $this->route('organizationId');

        if ($param instanceof Organization) {
            return $param;
        }

        return $param ? Organization::find($param) : null;
    }
}
