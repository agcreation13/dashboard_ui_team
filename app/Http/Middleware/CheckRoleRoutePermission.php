<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\RouteURLList;
use App\Models\RoleRoutePermission;
use Illuminate\Support\Facades\Auth;

class CheckRoleRoutePermission
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, "Unauthorized");
        }

        // ✅ Superadmin gets all access
        if ($user->role === "superadmin") {
            return $next($request);
        }
        //   if (in_array($user->role, ['representative', 'supervisor', 'admin'])) {
        //     // This runs ONLY for these roles
        //     // If you want to directly allow access to a specific page after login:
        //     if ($request->is('dashboard-leads/leads-sheet*')) {
        //         return $next($request); // ✅ Allow access
        //     }
        // }

        // Get current request path
        $currentPath = $request->path(); // e.g. dashboard-leads/leads-sheets/1
        // dd($currentPath);

        $paremert = [
            "active",
            "close",
            "standby",
            "unupdated",
            "completed",
            "visits",
            "estimates",
            "converted",
        ];
        // $paremert = 'active';
        $segments = explode("/", $currentPath);
        if (isset($segments[2])) {
            if (in_array($segments[2], $paremert)) {
                $segments[2] = "{id}";
            }
        }
        foreach ($segments as $index => $segment) {
            if (is_numeric($segment)) {
                $segments[$index] = "{id}";
            }
        }
        $normalizedPath = implode("/", $segments);
        // Join back
        $route = RouteURLList::where("url_name", $normalizedPath)->first();
        // dd($route);

        if (!$route) {
            abort(403, "URL not registered.");
        }

        // Get role permissions
        $permissions = RoleRoutePermission::where(
            "role_name",
            $user->role
        )->first();
        $permissions->url_ids = json_decode($permissions->url_ids, true);

        if (
            !$permissions ||
            !in_array($route->id, $permissions->url_ids ?? [])
        ) {
            abort(403, "You do not have permission to access this URL.");
        }

        return $next($request);
    }
}
