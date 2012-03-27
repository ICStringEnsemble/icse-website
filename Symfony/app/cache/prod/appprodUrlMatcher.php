<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appprodUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appprodUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = urldecode($pathinfo);

        // IcsePublicBundle_home
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'IcsePublicBundle_home');
            }
            return array (  '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::indexAction',  '_route' => 'IcsePublicBundle_home',);
        }

        // IcsePublicBundle_about
        if ($pathinfo === '/about') {
            return array (  '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::aboutAction',  '_route' => 'IcsePublicBundle_about',);
        }

        // IcsePublicBundle_support
        if ($pathinfo === '/support') {
            return array (  '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::supportAction',  '_route' => 'IcsePublicBundle_support',);
        }

        // IcsePublicBundle_contact
        if ($pathinfo === '/contact') {
            return array (  '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::contactAction',  '_route' => 'IcsePublicBundle_contact',);
        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
