<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsLibrarian
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, ['librarian', 'admin'])) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'Access restricted to librarians and administrators.'
            ], 403);
        }

        return $next($request);
    }
}
