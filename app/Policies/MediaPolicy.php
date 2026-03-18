<?php

namespace App\Policies;

use App\Models\Media;
use App\Models\User;

class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('media.view');
    }

    public function view(User $user, Media $media): bool
    {
        return $user->can('media.view');
    }

    public function create(User $user): bool
    {
        return $user->can('media.create');
    }

    public function update(User $user, Media $media): bool
    {
        return false;
    }

    public function delete(User $user, Media $media): bool
    {
        return $user->can('media.delete');
    }

    public function restore(User $user, Media $media): bool
    {
        return false;
    }

    public function forceDelete(User $user, Media $media): bool
    {
        return false;
    }
}
