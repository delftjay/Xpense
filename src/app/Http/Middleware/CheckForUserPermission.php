<?php
namespace App\Http\Middleware;

use Closure;
use Auth, Route, Log;

/**
 * 检查用户权限
 *
 * @author  
 *
 */
class CheckForUserPermission
{

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{
		$user = Auth::guard($guard)->user();
		$route_name = Route::currentRouteName();
		if ($user->denies($route_name)) {
			Log::notice('Permission denied', [
				'user' => [
					'id' => $user->id,
					'username' => $user->username
				],
				'route_name' => $route_name
			]);
			abort(403);
		}

		return $next($request);
	}
}
