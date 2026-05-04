<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SocialiteUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class SocialiteUserPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SocialiteUser');
    }

    public function view(AuthUser $authUser, SocialiteUser $socialiteUser): bool
    {
        return $authUser->can('View:SocialiteUser');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SocialiteUser');
    }

    public function update(AuthUser $authUser, SocialiteUser $socialiteUser): bool
    {
        return $authUser->can('Update:SocialiteUser');
    }

    public function delete(AuthUser $authUser, SocialiteUser $socialiteUser): bool
    {
        return $authUser->can('Delete:SocialiteUser');
    }

    public function restore(AuthUser $authUser, SocialiteUser $socialiteUser): bool
    {
        return $authUser->can('Restore:SocialiteUser');
    }

    public function forceDelete(AuthUser $authUser, SocialiteUser $socialiteUser): bool
    {
        return $authUser->can('ForceDelete:SocialiteUser');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SocialiteUser');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SocialiteUser');
    }

    public function replicate(AuthUser $authUser, SocialiteUser $socialiteUser): bool
    {
        return $authUser->can('Replicate:SocialiteUser');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SocialiteUser');
    }

}