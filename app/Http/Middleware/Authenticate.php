<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Helpers\UserAgent;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                // 后台系统
                if ($request->segment(1) == 'admin') {
                    if (UserAgent::i()->isPhone()) {
                        return redirect()->guest('admin/login');
                    }
                    return redirect()->guest('admin/view/login');
                }
                return redirect()->guest('login');
            }
        }

        return $next($request);
    }
}
