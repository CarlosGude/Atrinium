<?php

namespace App\Security\Voter;

use App\Entity\Exchange;
use App\Entity\FixerData;
use App\Entity\Sector;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class FixerDataVoter extends AbstractVoter
{
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof FixerData;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (!$subject instanceof FixerData) {
            return false;
        }

        return true;
    }
}
