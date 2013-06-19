<?php

use Symfony\Component\HttpFoundation\Request;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.



if ((isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], array(
        '127.0.0.1',
        '192.168.0.42',
        '::1',
        )))
   )
{
    if (!isset($_SERVER['PHP_AUTH_USER']))
    {
        header('WWW-Authenticate: Basic realm="Icse Dev"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'You must authenticate to access this page.';
        exit;
    }
    else if (!($_SERVER['PHP_AUTH_USER'] == "icse" && $_SERVER['PHP_AUTH_PW'] == "somepassword"))
    {
        echo "<p>{$_SERVER['REMOTE_ADDR']} is remote addr.</p>";
        header('HTTP/1.0 403 Forbidden');
        exit('You are not allowed to access this file.');
    }
}


$loader = require_once __DIR__.'/Symfony/app/bootstrap.php.cache';
require_once __DIR__.'/Symfony/app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
