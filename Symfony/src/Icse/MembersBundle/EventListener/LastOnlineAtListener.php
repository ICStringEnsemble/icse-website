<?php
namespace Icse\MembersBundle\EventListener;

use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\SecurityContextInterface; 
use Doctrine\ORM\EntityManager; 

class LastOnlineAtListener
{
    private $securityContext; 
    private $entityManager; 

    public function __construct(SecurityContextInterface $securityContext, EntityManager $entityManager)
    {
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
    } 

    public function onTerminate(PostResponseEvent $event)
    {
        $token = $this->securityContext->getToken();
        if ($token &&  $this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')
                   && !$this->securityContext->isGranted('ROLE_PREVIOUS_ADMIN')) { // if logged in and not being impersonated
            $user = $this->securityContext->getToken()->getUser();
            $user->setLastOnlineAt(new \DateTime()); 
            $this->entityManager->persist($user);
            $this->entityManager->flush(); 
        }
    }

}
