<?php

namespace Larabase\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return $user->hasAnyPermission([
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            'roles.restore',
            'roles.force-delete',
        ]);
    }

    public function view(?User $user): bool
    {
        return $user->can('roles.view');
    }

    public function create(User $user): bool
    {
        return $user->can('roles.create');
    }

    public function update(User $user, Role $role): bool
    {
        if(in_array($role->name, ['super-admin'])) return false;
        return $user->can('roles.update');
    }

    public function delete(User $user, Role $role): bool
    {
        if(in_array($role->name, ['admin', 'super-admin'])) return false;

        return $user->can('roles.delete');
    }

    public function restore(User $user): bool
    {
        return $user->can('roles.restore');
    }

    public function forceDelete(User $user): bool
    {
        return $user->can('roles.force-delete');
    }
}
