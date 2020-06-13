<?php

namespace App\Security\Voter;

use App\Entity\Filter;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class FilterVoter extends AbstractVoter
{
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof Filter;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        if (!$subject instanceof Filter) {
            return false;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            throw new UnauthorizedHttpException('Need to login');
        }
        return true;
    }
}
