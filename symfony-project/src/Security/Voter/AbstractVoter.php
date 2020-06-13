<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class AbstractVoter extends Voter
{
    public const CREATE = 'CREATE';
    public const UPDATE = 'UPDATE';
    public const DELETE = 'DELETE';
    public const READ = 'READ';

    protected function isRoleAdmin(array $roles): bool
    {
        return in_array(User::ROLE_ADMIN, $roles, true);
    }
}
