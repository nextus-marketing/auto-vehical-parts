<?php

namespace App\Http\Middleware;

use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!\Auth::user()){
            \Auth::logout();
            return redirect()->route('login');
        }
        if(\Auth::user()->status != 'ACTIVE'){
            \Auth::logout();
            return \Redirect::back()->withErrors(['You are not allowed to view this page. Please contact with admin.']);
        }

        // SuperAdmin has full unrestricted access to all admin operations
        if (\Auth::user()->hasRole('SuperAdmin')) {
            return $next($request);
        }

        $allow_user = false;
        $currentAction = \Route::currentRouteAction();
        if ($currentAction && strpos($currentAction, '@') !== false) {
            list($controller, $method) = explode('@', $currentAction);
            $controller = str_replace('App\Http\Controllers\\','',$controller);
            
            $permissions = \Auth::user()->getAllPermissions()->pluck('id');
            $permissions = \App\Models\Permission::whereIn('id',$permissions)->get();
            foreach($permissions as $permission){
                if($permission->permissiongroup && $permission->permissiongroup->controller == $controller){
                    if(is_array($permission->methods)){
                        foreach($permission->methods as $permission_method){
                            if($permission_method == $method){
                                $allow_user = true;
                                break 2;
                            }
                        }
                    }
                }
            }
        }

        if($allow_user){
            return $next($request);
        }else{
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized action: You do not have permission to perform this operation.'
                ], 403);
            }
            return abort(403);
        }

        return $next($request);
    }
}
