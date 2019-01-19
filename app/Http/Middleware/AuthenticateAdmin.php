<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use URL;
use Auth;

class AuthenticateAdmin
{
    protected $except = [
        'admin.view.home',
    ];

    /**
     * Handle an incoming request.
     *
     * @param         $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('admin')->user();
        if ($user->id === 1 || $user->super_user === 1) {
            return $next($request);
        }

        $previousUrl = URL::previous();
        $routeName = starts_with(Route::currentRouteName(), 'admin.') ? Route::currentRouteName() : 'admin.' . Route::currentRouteName();

        if ($request->user()->cannot($routeName) && !in_array($routeName, $this->except)) {
            if ($request->ajax() && ($request->getMethod() != 'GET')) {
                return response()->json([
                    'status' => -1,
                    'code' => 403,
                    'msg' => '您没有权限执行此操作',
                ]);
            } else {
                if ($request->segment(1) == 'admin' && $request->segment(2) == 'view') {
                    return response()->view('errors.403', compact('previousUrl'));
                }
                return response()->view('admin_rbac.errors.403', compact('previousUrl'));
            }
        }

        return $next($request);
    }
}
