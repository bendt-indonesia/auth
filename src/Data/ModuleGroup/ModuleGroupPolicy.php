<?php

namespace Bendt\Auth\Data\ModuleGroup;

use App\User;
use Bendt\Auth\Models\ModuleGroup as Model;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModuleGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User $user
     * @param  \Bendt\Auth\Models\ModuleGroup $model
     * @return mixed
     */
    public function view(User $user, Model $model)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function store(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User $user
     * @param  \Bendt\Auth\Models\ModuleGroup $model
     * @return mixed
     */
    public function update(User $user, Model $model)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User $user
     * @param  \Bendt\Auth\Models\ModuleGroup $model
     * @return mixed
     */
    public function destroy(User $user, Model $model)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User $user
     * @param  \Bendt\Auth\Models\ModuleGroup $model
     * @return mixed
     */
    public function restore(User $user, Model $model)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User $user
     * @param  \Bendt\Auth\Models\ModuleGroup $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        return false;
    }
}
