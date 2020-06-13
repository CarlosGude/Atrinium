<?php

namespace App\Security\Voter;

use App\Entity\Exchange;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ExchangeVoter extends AbstractVoter
{
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof Exchange;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (!$subject instanceof Exchange) {
            return false;
        }

        return true;
    }
}
