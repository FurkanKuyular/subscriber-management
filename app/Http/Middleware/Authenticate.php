<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * @throws AuthenticationException
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : throw new AuthenticationException();
    }
}
