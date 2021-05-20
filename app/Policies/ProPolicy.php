<?php

namespace App\Policies;

use App\Models\Pro;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProPolicy
{
    use HandlesAuthorization;
    public function before($user,$ability)
    {
        if ($user->role == 'admin') {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pro  $pro
     * @return mixed
     */
    public function view(User $user, Pro $pro)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pro  $pro
     * @return mixed
     */
    public function update(User $user, Pro $pro)
    {
        return $user->id == $pro->user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pro  $pro
     * @return mixed
     */
    public function delete(User $user, Pro $pro)
    {
        return $user->id == $pro->user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pro  $pro
     * @return mixed
     */
    public function restore(User $user, Pro $pro)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pro  $pro
     * @return mixed
     */
    public function forceDelete(User $user, Pro $pro)
    {
        //
    }
}
