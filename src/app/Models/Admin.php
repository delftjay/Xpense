<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use App\Observers\PasswordEncryptionObserver;
use Route;

/**
 * 管理员
 *
 * @author  
 *
 */
class Admin extends Authenticatable
{
	use Notifiable, SoftDeletes;

	protected $fillable = [
		'username',
		'password'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $appends = [
		'roles_name'
	];

	protected $casts = [
		'id' => 'integer',
		'username' => 'string',
		'password' => 'string',
		'remember_token' => 'string',
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
		'deleted_at' => 'datetime'
	];

	public static function boot()
	{
		parent::boot();

		static::observe(new PasswordEncryptionObserver());
	}

	/**
	 * 角色列表
	 */
	public function roles()
	{
		return $this->belongsToMany(Role::class, 'admin_roles')->withTimestamps();
	}

	/**
	 * 拥有的权限列表
	 */
	public function permissions()
	{
		return $this->belongsToMany(Permission::class, 'admin_permissions', 'admin_id', 'permission_key')->withTimestamps();
	}

	/**
	 * 操作记录列表
	 */
	public function operationRecords()
	{
		return $this->morphMany(OperationRecord::class, 'user');
	}

	/**
	 * 角色名
	 */
	public function getRolesNameAttribute()
	{
		return $this->id == 1 ? 'Root' : join(' & ', $this->roles->pluck('name')->all());
	}

	/**
	 * 拥有的所有权限列表
	 */
	public function getAllPermissionsAttribute()
	{
		static $cache = [];
		if ($this->exists && ! key_exists($this->id, $cache)) {

			// 取得拥有的权限列表。
			$permissions = Permission::oldest('group')->oldest('key')->get();
			if ($this->id !== 1) {
				$permissions = $permissions->filter(function ($item) {
					return has_permission($this, $item);
				});
			}

			$cache[$this->id] = $permissions;
		}
		return @$cache[$this->id];
	}

	/**
	 * 可访问的控制器列表
	 */
	public function getActionsAttribute()
	{
		static $cache = [];
		if ($this->exists && ! key_exists($this->id, $cache)) {
			$cache[$this->id] = new Collection();

			if ($this->id === 1) {

				// ID1的为超级管理员，拥有所有权限。
				foreach (Route::getRoutes() as $route) {
					$name = $route->getName();
					$middleware = (array) array_get($route->getAction(), 'middleware');
					if (! is_null($name) && in_array('permission:root', $middleware)) {
						$cache[$this->id]->push($name);
					}
				}
			} else {

				// 用户角色所拥有的权限。
				foreach ($this->roles()
					->with('permissions')
					->get() as $role) {
					foreach ($role->permissions as $permission) {
						$cache[$this->id] = $cache[$this->id]->merge($permission->actions);
					}
				}
				// 用户特别拥有的权限。
				foreach ($this->permissions as $permission) {
					$cache[$this->id] = $cache[$this->id]->merge($permission->actions);
				}
			}
			$cache[$this->id] = $cache[$this->id]->unique()->values();
		}
		return @$cache[$this->id];
	}

	/**
	 * 检查用户权限
	 *
	 * @param string $route_name 路由名称
	 * @return boolean 若没有权限则返回true
	 */
	public function denies($route_name)
	{
		if (! $this->actions->contains($route_name)) {
			return true;
		}
		return false;
	}
}
