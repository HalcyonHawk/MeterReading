<?php

namespace App\Policies;

use App\Models\MeterReading;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeterReadingPolicy
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
        //Admin only
        $permission = Permission::where('name', 'view-meter-reading')->first();
        return $user->hasRole($permission->roles);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MeterReading $meterReading)
    {
        //The user that made it or an admin
        $permission = Permission::where('name', 'view-meter-reading')->first();
        //User can delete their own reading
        return ($user->hasRole($permission->roles)) || ($meterReading->user_id === $user->user_id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //Admin users can not make meter readings.
        $permission = Permission::where('name', 'create-meter-reading')->first();
        return $user->hasRole($permission->roles);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MeterReading $meterReading)
    {
        //The user that made it or an admin
        $permission = Permission::where('name', 'update-meter-reading')->first();
        //User can delete their own reading
        return ($user->hasRole($permission->roles)) || ($meterReading->user_id === $user->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, MeterReading $meterReading)
    {
        //The user that made it or an admin
        $permission = Permission::where('name', 'delete-meter-reading')->first();
        //User can delete their own reading
        return ($user->hasRole($permission->roles)) || ($meterReading->user_id === $user->user_id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, MeterReading $meterReading)
    {
        //Admin only
        $permission = Permission::where('name', 'restore-meter-reading')->first();
        return $user->hasRole($permission->roles);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, MeterReading $meterReading)
    {
        //Admin only
        $permission = Permission::where('name', 'force-delete-meter-reading')->first();
        return $user->hasRole($permission->roles);
    }

    /**
     * Determine whether the user can upload a csv for the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function upload(User $user, MeterReading $meterReading)
    {
        //Admin only
        $permission = Permission::where('name', 'upload-meter-reading')->first();
        return $user->hasRole($permission->roles);
    }
}
