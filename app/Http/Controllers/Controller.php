<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function wantsJson(?Request $request = null): bool
    {
        $request ??= request();

        return $request->expectsJson() || $request->is('api/*');
    }

    protected function resolveOrganization(Request $request, mixed $organizationId = null): Organization
    {
        if ($organizationId instanceof Organization) {
            return $organizationId;
        }

        $id = $organizationId
            ?? $request->route('organizationId')
            ?? $request->route('organization');

        if ($id instanceof Organization) {
            return $id;
        }

        return Organization::findOrFail($id);
    }
}
