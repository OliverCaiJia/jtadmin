<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\AdminResponseFactory;
use \Illuminate\Http\Request;

/**
 * @author zhaoqiying
 */
class ValidateAdminMiddleware
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Validators';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $validator = null)
    {
        if ($request->isMethod('POST')) {
            $type = $request->segment(1);
            if ($validator) {
                $validator = $this->namespace . '\\' . studly_case($type) . '\\' . studly_case($validator) . 'Validator';
                $validator = new $validator($request->all());

                if (!$validator->passes()) {
                    return AdminResponseFactory::ajaxError($validator->errors()->first());
                }
            }
        }
        return $next($request);
    }
}
