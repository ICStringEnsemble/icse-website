<?php
namespace Icse\MembersBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LastOnlineAtListener
{
    private $token_storage; 
    private $auth_checker; 
    private $entity_manager;

    public function __construct(TokenStorageInterface $token_storage, AuthorizationCheckerInterface $auth_checker, EntityManager $entity_manager)
    {
        $this->token_storage = $token_storage;
        $this->auth_checker  = $auth_checker;
        $this->entity_manager = $entity_manager;
    } 

    public function onTerminate(PostResponseEvent $event)
    {
        $token = $this->token_storage->getToken();
        if ($token &&  $this->auth_checker->isGranted('IS_AUTHENTICATED_REMEMBERED')
                   && !$this->auth_checker->isGranted('ROLE_PREVIOUS_ADMIN'))  // if logged in and not being impersonated
        {
            $user = $token->getUser();
            $user->setLastOnlineAt(new \DateTime());
            try
            {
                $this->entity_manager->flush();
            }
            catch (\Doctrine\ORM\ORMException $e)
            {}
        }
    }

}
