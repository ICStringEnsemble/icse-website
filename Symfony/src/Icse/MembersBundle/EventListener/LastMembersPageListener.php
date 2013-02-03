<?php
namespace Icse\MembersBundle\EventListener;

use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\SecurityContextInterface; 
use Doctrine\ORM\EntityManager; 
use Symfony\Component\HttpFoundation\Cookie; 

class LastMembersPageListener
{
    public function __construct()
    {
    } 

    public function onResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();
        if (! $request->isXmlHttpRequest()) {
            $route = $request->get('_route');
            if (    strpos($route, 'IcseMembersBundle') !== false
                 && strpos($route, 'logout') == false ) {
                $response->headers->setCookie(new Cookie('last_members_page', $route, "2 weeks", "/arts/stringensemble")); 
            }
            if ($route == "IcsePublicBundle_login" || $response->isForbidden()) {
                $response->headers->clearCookie('last_members_page'); 
            }
        }
    }
}
