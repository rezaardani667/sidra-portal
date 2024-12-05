<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vaults;
use Illuminate\Auth\Access\HandlesAuthorization;

class VaultsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_vaults');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vaults $vaults): bool
    {
        return $user->can('view_vaults');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_vaults');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vaults $vaults): bool
    {
        return $user->can('update_vaults');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vaults $vaults): bool
    {
        return $user->can('delete_vaults');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_vaults');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Vaults $vaults): bool
    {
        return $user->can('force_delete_vaults');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_vaults');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Vaults $vaults): bool
    {
        return $user->can('restore_vaults');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_vaults');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Vaults $vaults): bool
    {
        return $user->can('replicate_vaults');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_vaults');
    }
}
