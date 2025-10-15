<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $routeName = $request->route()->getName(); // 'api.tasks.update'
        $permission = str_replace('api.', '', $routeName); // 'tasks.update'
        $permission = str_replace('.', '-', $permission);  // 'tasks-update'
// dd($permission, $user->can($permission));

        if (!$user || !$user->can($permission)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
