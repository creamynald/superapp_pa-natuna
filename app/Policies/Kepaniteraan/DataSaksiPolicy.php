<?php

declare(strict_types=1);

namespace App\Policies\Kepaniteraan;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Kepaniteraan\DataSaksi;
use Illuminate\Auth\Access\HandlesAuthorization;

class DataSaksiPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DataSaksi');
    }

    public function view(AuthUser $authUser, DataSaksi $dataSaksi): bool
    {
        return $authUser->can('View:DataSaksi');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DataSaksi');
    }

    public function update(AuthUser $authUser, DataSaksi $dataSaksi): bool
    {
        return $authUser->can('Update:DataSaksi');
    }

    public function delete(AuthUser $authUser, DataSaksi $dataSaksi): bool
    {
        return $authUser->can('Delete:DataSaksi');
    }

    public function restore(AuthUser $authUser, DataSaksi $dataSaksi): bool
    {
        return $authUser->can('Restore:DataSaksi');
    }

    public function forceDelete(AuthUser $authUser, DataSaksi $dataSaksi): bool
    {
        return $authUser->can('ForceDelete:DataSaksi');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DataSaksi');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DataSaksi');
    }

    public function replicate(AuthUser $authUser, DataSaksi $dataSaksi): bool
    {
        return $authUser->can('Replicate:DataSaksi');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DataSaksi');
    }

}