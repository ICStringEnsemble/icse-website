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
        $pathinfo = rawurldecode($pathinfo);

        // IcseMembersBundle_home
        if ($pathinfo === '/members') {
            return array (  '_controller' => 'Icse\\MembersBundle\\Controller\\DefaultController::indexAction',  '_route' => 'IcseMembersBundle_home',);
        }

        // IcseMembersBundle_logout
        if ($pathinfo === '/logout') {
            return array('_route' => 'IcseMembersBundle_logout');
        }

        // IcseMembersBundle_account_settings
        if ($pathinfo === '/members/account') {
            return array (  '_controller' => 'Icse\\MembersBundle\\Controller\\AccountSettingsController::indexAction',  '_route' => 'IcseMembersBundle_account_settings',);
        }

        // IcseMembersBundle_image_uploads
        if ($pathinfo === '/members/admin/images') {
            return array (  '_controller' => 'Icse\\MembersBundle\\Controller\\AdminController::imageUploadsAction',  '_route' => 'IcseMembersBundle_image_uploads',);
        }

        // IcseMembersBundle_receive_upload
        if ($pathinfo === '/members/admin/upload') {
            return array (  '_controller' => 'Icse\\MembersBundle\\Controller\\AdminController::uploadAction',  '_route' => 'IcseMembersBundle_receive_upload',);
        }

        // IcseMembersBundle_confirm_upload
        if ($pathinfo === '/members/admin/uploadconfirm') {
            return array (  '_controller' => 'Icse\\MembersBundle\\Controller\\AdminController::confirmUploadAction',  '_route' => 'IcseMembersBundle_confirm_upload',);
        }

        // IcseMembersBundle_migrateDB
        if ($pathinfo === '/members/superadmin/migratedb') {
            return array (  '_controller' => 'Icse\\MembersBundle\\Controller\\SuperAdminController::migrateDBAction',  '_route' => 'IcseMembersBundle_migrateDB',);
        }

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

        // IcsePublicBundle_event
        if (0 === strpos($pathinfo, '/events') && preg_match('#^/events/(?P<id>[^/]+)(?:/(?P<slug>[^/]+))?$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Icse\\PublicBundle\\Controller\\EventsController::eventAction',  'slug' => '',)), array('_route' => 'IcsePublicBundle_event'));
        }

        // IcsePublicBundle_join
        if ($pathinfo === '/join') {
            return array (  '_controller' => 'Icse\\PublicBundle\\Controller\\SignUpController::joinAction',  'freshers' => false,  '_route' => 'IcsePublicBundle_join',);
        }

        // IcsePublicBundle_join_freshers
        if ($pathinfo === '/join/freshersfair') {
            return array (  '_controller' => 'Icse\\PublicBundle\\Controller\\SignUpController::joinAction',  'freshers' => true,  '_route' => 'IcsePublicBundle_join_freshers',);
        }

        // IcsePublicBundle_signup_results_tmp
        if ($pathinfo === '/view/all/signups/from/freshers/fair') {
            return array (  '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::signupAction',  '_route' => 'IcsePublicBundle_signup_results_tmp',);
        }

        // IcsePublicBundle_query_username
        if ($pathinfo === '/join/query_username') {
            return array (  '_controller' => 'Icse\\PublicBundle\\Controller\\SignUpController::query_usernameAction',  '_format' => 'json',  '_route' => 'IcsePublicBundle_query_username',);
        }

        // IcsePublicBundle_support
        if ($pathinfo === '/support') {
            return array (  '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::supportAction',  '_route' => 'IcsePublicBundle_support',);
        }

        // IcsePublicBundle_contact
        if ($pathinfo === '/contact') {
            return array (  '_controller' => 'Icse\\PublicBundle\\Controller\\DefaultController::contactAction',  '_route' => 'IcsePublicBundle_contact',);
        }

        // IcsePublicBundle_login
        if ($pathinfo === '/login') {
            return array (  '_controller' => 'Icse\\PublicBundle\\Controller\\SecurityController::loginAction',  '_route' => 'IcsePublicBundle_login',);
        }

        // IcsePublicBundle_login_check
        if ($pathinfo === '/login_check') {
            return array('_route' => 'IcsePublicBundle_login_check');
        }

        // IcsePublicBundle_resource
        if (0 === strpos($pathinfo, '/resources') && preg_match('#^/resources/(?P<type>[^/]+)/(?P<file>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Icse\\PublicBundle\\Controller\\ResourcesController::resourceAction',)), array('_route' => 'IcsePublicBundle_resource'));
        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
