<?php

namespace Larabase\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return $user->hasAnyPermission([
            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',
            'permissions.restore',
            'permissions.force-delete',
        ]);
    }

    public function view(?User $user): bool
    {
        return $user->can('permissions.view');
    }

    public function create(User $user): bool
    {
        return $user->can('permissions.create');
    }

    public function update(User $user): bool
    {
        return $user->can('permissions.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('permissions.delete');
    }

    public function restore(User $user): bool
    {
        return $user->can('permissions.restore');
    }

    public function forceDelete(User $user): bool
    {
        return $user->can('permissions.force-delete');
    }
}
