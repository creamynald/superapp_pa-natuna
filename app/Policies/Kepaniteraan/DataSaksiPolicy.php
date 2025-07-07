<?php

namespace App\Policies\Kepaniteraan;

use App\Models\User;
use App\Models\Kepaniteraan\DataSaksi;
use Illuminate\Auth\Access\HandlesAuthorization;

class DataSaksiPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_kepaniteraan::data::saksi');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DataSaksi $dataSaksi): bool
    {
        return $user->can('view_kepaniteraan::data::saksi');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_kepaniteraan::data::saksi');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DataSaksi $dataSaksi): bool
    {
        return $user->can('update_kepaniteraan::data::saksi');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DataSaksi $dataSaksi): bool
    {
        return $user->can('delete_kepaniteraan::data::saksi');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_kepaniteraan::data::saksi');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, DataSaksi $dataSaksi): bool
    {
        return $user->can('force_delete_kepaniteraan::data::saksi');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_kepaniteraan::data::saksi');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, DataSaksi $dataSaksi): bool
    {
        return $user->can('restore_kepaniteraan::data::saksi');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_kepaniteraan::data::saksi');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, DataSaksi $dataSaksi): bool
    {
        return $user->can('replicate_kepaniteraan::data::saksi');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_kepaniteraan::data::saksi');
    }
}
