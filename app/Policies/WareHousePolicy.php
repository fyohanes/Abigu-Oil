<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WareHouse;
use Illuminate\Auth\Access\HandlesAuthorization;

class WareHousePolicy
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
        $user = Auth()->user();
        if($user->role_id==1){
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WareHouse  $wareHouse
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, WareHouse $wareHouse)
    {
        $user = Auth()->user();
        if($user->role_id==1){
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $user = Auth()->user();
        if($user->role_id==1){
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WareHouse  $wareHouse
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, WareHouse $wareHouse)
    {
        $user = Auth()->user();
        if($user->role_id==1){
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WareHouse  $wareHouse
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, WareHouse $wareHouse)
    {
        $user = Auth()->user();
        if($user->role_id==1){
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WareHouse  $wareHouse
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, WareHouse $wareHouse)
    {
        $user = Auth()->user();
        if($user->role_id==1){
            return true;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WareHouse  $wareHouse
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, WareHouse $wareHouse)
    {
        $user = Auth()->user();
        if($user->role_id==1){
            return true;
        }
    }
}