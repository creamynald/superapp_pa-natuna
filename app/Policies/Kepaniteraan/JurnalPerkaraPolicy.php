<?php

declare(strict_types=1);

namespace App\Policies\Kepaniteraan;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Kepaniteraan\JurnalPerkara;
use Illuminate\Auth\Access\HandlesAuthorization;

class JurnalPerkaraPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JurnalPerkara');
    }

    public function view(AuthUser $authUser, JurnalPerkara $jurnalPerkara): bool
    {
        return $authUser->can('View:JurnalPerkara');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JurnalPerkara');
    }

    public function update(AuthUser $authUser, JurnalPerkara $jurnalPerkara): bool
    {
        return $authUser->can('Update:JurnalPerkara');
    }

    public function delete(AuthUser $authUser, JurnalPerkara $jurnalPerkara): bool
    {
        return $authUser->can('Delete:JurnalPerkara');
    }

    public function restore(AuthUser $authUser, JurnalPerkara $jurnalPerkara): bool
    {
        return $authUser->can('Restore:JurnalPerkara');
    }

    public function forceDelete(AuthUser $authUser, JurnalPerkara $jurnalPerkara): bool
    {
        return $authUser->can('ForceDelete:JurnalPerkara');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JurnalPerkara');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JurnalPerkara');
    }

    public function replicate(AuthUser $authUser, JurnalPerkara $jurnalPerkara): bool
    {
        return $authUser->can('Replicate:JurnalPerkara');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JurnalPerkara');
    }

}