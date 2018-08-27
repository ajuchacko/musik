<?php

namespace App\Policies;

use App\User;
use App\Playlist;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlaylistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the playlist.
     *
     * @param  \App\User  $user
     * @param  \App\Playlist  $playlist
     * @return mixed
     */
    public function view(User $user, Playlist $playlist)
    {
        //
    }

    /**
     * Determine whether the user can create playlists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the playlist.
     *
     * @param  \App\User  $user
     * @param  \App\Playlist  $playlist
     * @return mixed
     */
    public function update(User $user, Playlist $playlist)
    {
        //
    }

    /**
     * Determine whether the user can delete the playlist.
     *
     * @param  \App\User  $user
     * @param  \App\Playlist  $playlist
     * @return mixed
     */
    public function destroy(User $user, Playlist $playlist)
    {
        return $user->id == $playlist->user_id;
    }
}
