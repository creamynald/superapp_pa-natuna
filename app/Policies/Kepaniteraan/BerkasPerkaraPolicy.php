<?php

declare(strict_types=1);

namespace App\Policies\Kepaniteraan;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Kepaniteraan\BerkasPerkara;
use Illuminate\Auth\Access\HandlesAuthorization;

class BerkasPerkaraPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BerkasPerkara');
    }

    public function view(AuthUser $authUser, BerkasPerkara $berkasPerkara): bool
    {
        return $authUser->can('View:BerkasPerkara');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BerkasPerkara');
    }

    public function update(AuthUser $authUser, BerkasPerkara $berkasPerkara): bool
    {
        return $authUser->can('Update:BerkasPerkara');
    }

    public function delete(AuthUser $authUser, BerkasPerkara $berkasPerkara): bool
    {
        return $authUser->can('Delete:BerkasPerkara');
    }

    public function restore(AuthUser $authUser, BerkasPerkara $berkasPerkara): bool
    {
        return $authUser->can('Restore:BerkasPerkara');
    }

    public function forceDelete(AuthUser $authUser, BerkasPerkara $berkasPerkara): bool
    {
        return $authUser->can('ForceDelete:BerkasPerkara');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BerkasPerkara');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BerkasPerkara');
    }

    public function replicate(AuthUser $authUser, BerkasPerkara $berkasPerkara): bool
    {
        return $authUser->can('Replicate:BerkasPerkara');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BerkasPerkara');
    }

}