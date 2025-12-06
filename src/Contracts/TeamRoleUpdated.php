<?php

namespace LiraUi\Team\Contracts;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface TeamRoleUpdated
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse(Request $request): Response;
}
