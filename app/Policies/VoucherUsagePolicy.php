<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\VoucherUsage;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoucherUsagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VoucherUsage');
    }

    public function view(AuthUser $authUser, VoucherUsage $voucherUsage): bool
    {
        return $authUser->can('View:VoucherUsage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VoucherUsage');
    }

    public function update(AuthUser $authUser, VoucherUsage $voucherUsage): bool
    {
        return $authUser->can('Update:VoucherUsage');
    }

    public function delete(AuthUser $authUser, VoucherUsage $voucherUsage): bool
    {
        return $authUser->can('Delete:VoucherUsage');
    }

    public function restore(AuthUser $authUser, VoucherUsage $voucherUsage): bool
    {
        return $authUser->can('Restore:VoucherUsage');
    }

    public function forceDelete(AuthUser $authUser, VoucherUsage $voucherUsage): bool
    {
        return $authUser->can('ForceDelete:VoucherUsage');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:VoucherUsage');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:VoucherUsage');
    }

    public function replicate(AuthUser $authUser, VoucherUsage $voucherUsage): bool
    {
        return $authUser->can('Replicate:VoucherUsage');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:VoucherUsage');
    }

}