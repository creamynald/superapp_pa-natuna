<?php

declare(strict_types=1);

namespace App\Policies\Kepaniteraan;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Kepaniteraan\PeminjamanBerkasPerkara;
use Illuminate\Auth\Access\HandlesAuthorization;

class PeminjamanBerkasPerkaraPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PeminjamanBerkasPerkara');
    }

    public function view(AuthUser $authUser, PeminjamanBerkasPerkara $peminjamanBerkasPerkara): bool
    {
        return $authUser->can('View:PeminjamanBerkasPerkara');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PeminjamanBerkasPerkara');
    }

    public function update(AuthUser $authUser, PeminjamanBerkasPerkara $peminjamanBerkasPerkara): bool
    {
        return $authUser->can('Update:PeminjamanBerkasPerkara');
    }

    public function delete(AuthUser $authUser, PeminjamanBerkasPerkara $peminjamanBerkasPerkara): bool
    {
        return $authUser->can('Delete:PeminjamanBerkasPerkara');
    }

    public function restore(AuthUser $authUser, PeminjamanBerkasPerkara $peminjamanBerkasPerkara): bool
    {
        return $authUser->can('Restore:PeminjamanBerkasPerkara');
    }

    public function forceDelete(AuthUser $authUser, PeminjamanBerkasPerkara $peminjamanBerkasPerkara): bool
    {
        return $authUser->can('ForceDelete:PeminjamanBerkasPerkara');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PeminjamanBerkasPerkara');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PeminjamanBerkasPerkara');
    }

    public function replicate(AuthUser $authUser, PeminjamanBerkasPerkara $peminjamanBerkasPerkara): bool
    {
        return $authUser->can('Replicate:PeminjamanBerkasPerkara');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PeminjamanBerkasPerkara');
    }

}