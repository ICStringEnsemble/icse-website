<?php

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * appprodUrlGenerator
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appprodUrlGenerator extends Icse\PublicBundle\Routing\Generator\UrlGenerator
{
    static private $declaredRoutes = array(
        'IcseMembersBundle_home' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\MembersBundle\\Controller\\DefaultController::indexAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/members',    ),  ),),
        'IcseMembersBundle_logout' => array (  0 =>   array (  ),  1 =>   array (  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/logout',    ),  ),),
        'IcseMembersBundle_account_settings' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\MembersBundle\\Controller\\AccountSettingsController::indexAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/members/account',    ),  ),),
        'IcseMembersBundle_image_uploads' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\MembersBundle\\Controller\\AdminController::imageUploadsAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/members/admin/images',    ),  ),),
        'IcseMembersBundle_receive_upload' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\MembersBundle\\Controller\\AdminController::uploadAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/members/admin/upload',    ),  ),),
        'IcseMembersBundle_confirm_upload' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\MembersBundle\\Controller\\AdminController::confirmUploadAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/members/admin/uploadconfirm',    ),  ),),
        'IcseMembersBundle_migrateDB' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\MembersBundle\\Controller\\SuperAdminController::migrateDBAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/members/superadmin/migratedb',    ),  ),),
        'IcsePublicBundle_home' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::indexAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/',    ),  ),),
        'IcsePublicBundle_about' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::aboutAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/about',    ),  ),),
        'IcsePublicBundle_event' => array (  0 =>   array (    0 => 'id',    1 => 'slug',  ),  1 =>   array (    '_controller' => 'Icse\\PublicBundle\\Controller\\EventsController::eventAction',    'slug' => '',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '/',      2 => '[^/]+',      3 => 'slug',    ),    1 =>     array (      0 => 'variable',      1 => '/',      2 => '[^/]+',      3 => 'id',    ),    2 =>     array (      0 => 'text',      1 => '/events',    ),  ),),
        'IcsePublicBundle_join' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\PublicBundle\\Controller\\SignUpController::joinAction',    'freshers' => false,  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/join',    ),  ),),
        'IcsePublicBundle_join_freshers' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\PublicBundle\\Controller\\SignUpController::joinAction',    'freshers' => true,  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/join/freshersfair',    ),  ),),
        'IcsePublicBundle_signup_results_tmp' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::signupAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/view/all/signups/from/freshers/fair',    ),  ),),
        'IcsePublicBundle_query_username' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\PublicBundle\\Controller\\SignUpController::query_usernameAction',    '_format' => 'json',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/join/query_username',    ),  ),),
        'IcsePublicBundle_support' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::supportAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/support',    ),  ),),
        'IcsePublicBundle_contact' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::contactAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/contact',    ),  ),),
        'IcsePublicBundle_login' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Icse\\PublicBundle\\Controller\\SecurityController::loginAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/login',    ),  ),),
        'IcsePublicBundle_login_check' => array (  0 =>   array (  ),  1 =>   array (  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/login_check',    ),  ),),
        'IcsePublicBundle_resource' => array (  0 =>   array (    0 => 'type',    1 => 'file',  ),  1 =>   array (    '_controller' => 'Icse\\PublicBundle\\Controller\\ResourcesController::resourceAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '/',      2 => '[^/]+',      3 => 'file',    ),    1 =>     array (      0 => 'variable',      1 => '/',      2 => '[^/]+',      3 => 'type',    ),    2 =>     array (      0 => 'text',      1 => '/resources',    ),  ),),
    );

    /**
     * Constructor.
     */
    public function __construct(RequestContext $context, LoggerInterface $logger = null)
    {
        $this->context = $context;
        $this->logger = $logger;
    }

    public function generate($name, $parameters = array(), $absolute = false)
    {
        if (!isset(self::$declaredRoutes[$name])) {
            throw new RouteNotFoundException(sprintf('Route "%s" does not exist.', $name));
        }

        list($variables, $defaults, $requirements, $tokens) = self::$declaredRoutes[$name];

        return $this->doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $absolute);
    }
}
