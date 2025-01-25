<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->is('api/*') || $request->is('oauth/*')) {
            throw new AuthenticationException(response([
                'errors'=> 'Unauthorized.'
            ],401));
        }

        return $request->expectsJson() ? null : '';
    }
}
