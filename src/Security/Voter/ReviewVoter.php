<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ReviewVoter extends Voter
{
    protected function supports(string $attribute, $review): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['DELETE_REVIEW'])
            && $review instanceof \App\Entity\Review;
    }

    protected function voteOnAttribute(string $attribute, $review, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'DELETE_REVIEW':
                if($user == $review->getUser() || $user->isAdmin()){
                    return true;
                }
                return false;

                break;
        }

        return false;
    }
}
