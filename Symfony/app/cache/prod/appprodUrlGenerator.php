<?php

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\RouteNotFoundException;


/**
 * appprodUrlGenerator
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appprodUrlGenerator extends Symfony\Component\Routing\Generator\UrlGenerator
{
    static private $declaredRouteNames = array(
       'IcsePublicBundle_home' => true,
       'IcsePublicBundle_about' => true,
       'IcsePublicBundle_support' => true,
       'IcsePublicBundle_contact' => true,
    );

    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function generate($name, $parameters = array(), $absolute = false)
    {
        if (!isset(self::$declaredRouteNames[$name])) {
            throw new RouteNotFoundException(sprintf('Route "%s" does not exist.', $name));
        }

        $escapedName = str_replace('.', '__', $name);

        list($variables, $defaults, $requirements, $tokens) = $this->{'get'.$escapedName.'RouteInfo'}();

        return $this->doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $absolute);
    }

    private function getIcsePublicBundle_homeRouteInfo()
    {
        return array(array (), array (  '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::indexAction',), array (), array (  0 =>   array (    0 => 'text',    1 => '/',  ),));
    }

    private function getIcsePublicBundle_aboutRouteInfo()
    {
        return array(array (), array (  '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::aboutAction',), array (), array (  0 =>   array (    0 => 'text',    1 => '/about',  ),));
    }

    private function getIcsePublicBundle_supportRouteInfo()
    {
        return array(array (), array (  '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::supportAction',), array (), array (  0 =>   array (    0 => 'text',    1 => '/support',  ),));
    }

    private function getIcsePublicBundle_contactRouteInfo()
    {
        return array(array (), array (  '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::contactAction',), array (), array (  0 =>   array (    0 => 'text',    1 => '/contact',  ),));
    }
}
