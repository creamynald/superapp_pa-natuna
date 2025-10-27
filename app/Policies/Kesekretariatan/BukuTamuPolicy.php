<?php

declare(strict_types=1);

namespace App\Policies\Kesekretariatan;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Kesekretariatan\BukuTamu;
use Illuminate\Auth\Access\HandlesAuthorization;

class BukuTamuPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BukuTamu');
    }

    public function view(AuthUser $authUser, BukuTamu $bukuTamu): bool
    {
        return $authUser->can('View:BukuTamu');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BukuTamu');
    }

    public function update(AuthUser $authUser, BukuTamu $bukuTamu): bool
    {
        return $authUser->can('Update:BukuTamu');
    }

    public function delete(AuthUser $authUser, BukuTamu $bukuTamu): bool
    {
        return $authUser->can('Delete:BukuTamu');
    }

    public function restore(AuthUser $authUser, BukuTamu $bukuTamu): bool
    {
        return $authUser->can('Restore:BukuTamu');
    }

    public function forceDelete(AuthUser $authUser, BukuTamu $bukuTamu): bool
    {
        return $authUser->can('ForceDelete:BukuTamu');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BukuTamu');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BukuTamu');
    }

    public function replicate(AuthUser $authUser, BukuTamu $bukuTamu): bool
    {
        return $authUser->can('Replicate:BukuTamu');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BukuTamu');
    }

}