<?php

namespace App\Security\Voter;

use App\Entity\Sector;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SectorVoter extends AbstractVoter
{
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof Sector;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (!$subject instanceof Sector) {
            return false;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            throw new UnauthorizedHttpException('Need to login');
        }

        if ($this->isRoleAdmin($user->getRoles())) {
            return true;
        }

        return false;
    }
}
