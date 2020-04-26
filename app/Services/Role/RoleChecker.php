<?php

namespace App\Services\Role;

use App\Models\User;

/**
 * Class RoleChecker
 * @package App\Services\Role
 */
class RoleChecker
{
    /**
     * @param User $user
     * @param string $role
     * @return bool
     */
    public function check(User $user, string $role)
    {
        // Admin has everything
        if ($user->hasRole(UserRole::ROLE_ADMIN)) {
            return true;
        } elseif ($user->hasRole(UserRole::ROLE_USER)) {
            $userRoles = UserRole::getAllowedRoles(UserRole::ROLE_USER);

            if (in_array($role, $userRoles)) {
                return true;
            }
        }

        return $user->hasRole($role);
    }
}
