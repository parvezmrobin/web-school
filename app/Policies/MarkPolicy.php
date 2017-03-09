<?php

namespace App\Policies;

use App\User;
use App\Mark;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the mark.
     *
     * @param  \App\User  $user
     * @param  \App\Mark  $mark
     * @return mixed
     */
    public function view(User $user, Mark $mark)
    {
        //
    }

    /**
     * Determine whether the user can create marks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the mark.
     *
     * @param  \App\User  $user
     * @param  \App\Mark  $mark
     * @return mixed
     */
    public function update(User $user, Mark $mark)
    {
        //
    }

    /**
     * Determine whether the user can delete the mark.
     *
     * @param  \App\User  $user
     * @param  \App\Mark  $mark
     * @return mixed
     */
    public function delete(User $user, Mark $mark)
    {
        //
    }
}
