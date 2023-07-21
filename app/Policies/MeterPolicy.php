<?php

namespace App\Policies;

use App\Models\Meter;
use App\Models\User;
use App\Models\Permission;

use Illuminate\Auth\Access\HandlesAuthorization;

class MeterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //Any
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meter  $meter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Meter $meter)
    {
        //Any
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //Admin only
        $permission = Permission::where('name', 'create-meter')->first();
        return $user->hasRole($permission->roles);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meter  $meter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Meter $meter)
    {
        //Admin only
        $permission = Permission::where('name', 'update-meter')->first();
        return $user->hasRole($permission->roles);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meter  $meter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Meter $meter)
    {
        //Admin only
        $permission = Permission::where('name', 'delete-meter')->first();
        return $user->hasRole($permission->roles);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meter  $meter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Meter $meter)
    {
        //Admin only
        $permission = Permission::where('name', 'restore-meter')->first();
        return $user->hasRole($permission->roles);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meter  $meter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Meter $meter)
    {
        //Admin only
        $permission = Permission::where('name', 'force-delete-meter')->first();
        return $user->hasRole($permission->roles);
    }
}
