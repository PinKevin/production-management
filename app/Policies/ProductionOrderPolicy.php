<?php

namespace App\Policies;

use App\Enum\UserDepartment;
use App\Enum\UserRole;
use App\Models\Production\ProductionOrder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductionOrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return ($user->department == UserDepartment::PRODUCTION);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductionOrder $productionOrder): bool
    {
        return ($user->department == UserDepartment::PRODUCTION);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductionOrder $productionOrder): bool
    {
        return ($user->department == UserDepartment::PRODUCTION);
    }

    public function makeReport(User $user)
    {
        return ($user->department == UserDepartment::PRODUCTION);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductionOrder $productionOrder): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductionOrder $productionOrder): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProductionOrder $productionOrder): bool
    {
        return false;
    }
}
