<?php

namespace Bendt\Auth\Policy;

use App\User;
use Bendt\Auth\Models\Module as Model;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User $user
     * @param  \Bendt\Auth\Models\Module $model
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
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User $user
     * @param  \Bendt\Auth\Models\Module $model
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
     * @param  \Bendt\Auth\Models\Module $model
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
     * @param  \Bendt\Auth\Models\Module $model
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
     * @param  \Bendt\Auth\Models\Module $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        return false;
    }
}
