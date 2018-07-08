<?php

namespace App\Policies;

use App\User;
use App\Models\Album;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class AlbumPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the album.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Album  $album
     * @return mixed
     */
    public function view(User $user, Album $album)
    {
        return $user->id === $album->user_id;
    }

    /**
     * Determine whether the user can create albums.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
       return true;
    }

    /**
     * Determine whether the user can update the album.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Album  $album
     * @return mixed
     */
    public function update(User $user, Album $album)
    {
        if(Auth::user()->isAdmin()){
            return true;
        }
        return $user->id === $album->user_id;
    }

    /**
     * Determine whether the user can delete the album.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Album  $album
     * @return mixed
     */
    public function delete(User $user, Album $album)
    {
        return $user->id === $album->user_id;
    }
}
