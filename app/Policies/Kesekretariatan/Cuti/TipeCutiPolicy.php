<?php

declare(strict_types=1);

namespace App\Policies\Kesekretariatan\Cuti;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Kesekretariatan\Cuti\TipeCuti;
use Illuminate\Auth\Access\HandlesAuthorization;

class TipeCutiPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TipeCuti');
    }

    public function view(AuthUser $authUser, TipeCuti $tipeCuti): bool
    {
        return $authUser->can('View:TipeCuti');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TipeCuti');
    }

    public function update(AuthUser $authUser, TipeCuti $tipeCuti): bool
    {
        return $authUser->can('Update:TipeCuti');
    }

    public function delete(AuthUser $authUser, TipeCuti $tipeCuti): bool
    {
        return $authUser->can('Delete:TipeCuti');
    }

    public function restore(AuthUser $authUser, TipeCuti $tipeCuti): bool
    {
        return $authUser->can('Restore:TipeCuti');
    }

    public function forceDelete(AuthUser $authUser, TipeCuti $tipeCuti): bool
    {
        return $authUser->can('ForceDelete:TipeCuti');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TipeCuti');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TipeCuti');
    }

    public function replicate(AuthUser $authUser, TipeCuti $tipeCuti): bool
    {
        return $authUser->can('Replicate:TipeCuti');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TipeCuti');
    }

}