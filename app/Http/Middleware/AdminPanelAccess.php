<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AdminPanelAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access to login and other public routes
        $publicRoutes = [
            'admin/login',
            'admin/logout',
            'admin/forgot-password',
            'admin/reset-password',
        ];

        if (in_array($request->path(), $publicRoutes)) {
            return $next($request);
        }

        /** @var User|null $user */
        $user = Auth::user();
        
        if (!$user || !method_exists($user, 'hasPermission') || !$user->hasPermission('admin.panel.access')) {
            abort(403, 'Access denied. You do not have permission to access the admin panel.');
        }

        return $next($request);
    }
}
