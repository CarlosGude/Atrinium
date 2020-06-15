<?php

namespace App\Security\Voter;

use App\Entity\Company;
use App\Entity\Sector;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CompanyVoter extends AbstractVoter
{
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof Company;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        if (!$subject instanceof Company) {
            return false;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            throw new UnauthorizedHttpException('Need to login');
        }

        if ($attribute === parent::UPDATE || $attribute === parent::DELETE){
            $sector = $subject->getSector();
            $existSector = $user->getAuthorizedSectors()->filter(static function (Sector $authorizedSectors) use ($sector){
                return $authorizedSectors === $sector;
            });

            return count($existSector) > 0;

        }
        return true;
    }
}
