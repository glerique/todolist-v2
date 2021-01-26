<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoter extends Voter
{


    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    protected function supports($attribute, $subject)
    {
        
        return in_array($attribute, ['DELETE'])
            && $subject instanceof Task;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
                           
            case 'DELETE':
                
                if ($subject->getUser() === $user) {
                    return true;
                }

                if ($this->security->isGranted('ROLE_ADMIN') 
                    && $subject->getUser()->getUsername() === "anonyme") { 
                    return true;
                }                
        }  
    }
}