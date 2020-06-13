<?php

namespace App\Security\Voter;

use App\Entity\FixerData;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

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
