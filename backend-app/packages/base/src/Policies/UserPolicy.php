<?php

namespace Larabase\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
         return $user->can('users');
    }

    public function view(?User $user): bool
    {
         return true;
    }

    public function create(User $user): bool
    {
         return true;
    }

    public function update(User $user): bool
    {
         return true;
    }

    public function delete(User $user): bool
    {
         return true;
    }

    public function restore(User $user): bool
    {
         return true;
    }

    public function forceDelete(User $user): bool
    {
         return true;
    }
}
