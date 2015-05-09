<?php  namespace Artesaos\Defender\Traits;

/**
 * Class HasUserRolesTrait
 *
 * @package Artesaos\Defender\Traits
 */
trait HasUserRolesTrait {

	/**
	 * Many-to-many role-user relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany(
			config('defender.role_model'), config('defender.role_user_table'), 'user_id', config('defender.role_key')
		);
	}

	/**
	 * Returns if the given user has an specific role
	 *
	 * @param $role
	 * @return bool
	 */
	public function hasRole($role)
	{
		$roles = $this->roles->lists('name')->all();

		return in_array($role, $roles);
	}

	/**
	 * Check if the user has the given permission using
	 * only his roles.
	 *
	 * @param $permission
	 * @return bool
	 */
	public function canWithRolePermissions($permission)
	{
		// Search roles permission
		foreach ($this->roles as $role)
		{
			if ($rolePermission = $role->getPermission($permission))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Attach the given role.
	 *
	 * @param $role
	 */
	public function attachRole($role)
	{
		return $this->roles()->attach($role);
	}

	/**
	 * Detach the given role from the model.
	 *
	 * @param $role
	 * @return int
	 */
	public function detachRole($role)
	{
		return $this->roles()->detach($role);
	}

	/**
	 * Sync the given roles
	 *
	 * @param array $roles
	 * @return array
	 */
	public function syncRoles(array $roles)
	{
		return $this->roles()->sync($roles);
	}

}
