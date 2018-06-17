<?php

namespace App\Policies;

use App\User;
use App\Models\Photo;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class PhotoPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability){
        if ($user->isAdmin()){
            return true;
        }
    }

    /**
     * Determine whether the user can view the photo.
     *
     * @param  \App\User  $user
     * @param  \App\Photo  $photo
     * @return mixed
     */
    public function view(User $user, Photo $photo)
    {
        //if (Auth::user()->isAdmin()){
          //  return true;
        //}
        return $user->id === $photo->album->user_id;
    }

    public function index(User $user, Photo $photo)
    {
        //if (Auth::user()->isAdmin()){
        //  return true;
        //}
        return $user->id === $photo->album->user_id;
    }

    /**
     * Determine whether the user can create photos.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the photo.
     *
     * @param  \App\User  $user
     * @param  \App\Photo  $photo
     * @return mixed
     */
    public function update(User $user, Photo $photo)
    {
        //if (Auth::user()->isAdmin()){
          //  return true;
        //}
        return $user->id === $photo->album->user_id;
    }

    /**
     * Determine whether the user can delete the photo.
     *
     * @param  \App\User  $user
     * @param  \App\Photo  $photo
     * @return mixed
     */
    public function delete(User $user, Photo $photo)
    {
        //0if (Auth::user()->isAdmin()){
           // return true;
        //}
        return $user->id === $photo->album->user_id;
    }
}
