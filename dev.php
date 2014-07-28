<?php

use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/Symfony/app/bootstrap.php.cache';
require_once __DIR__.'/Symfony/app/AppKernel.php';

$symfony_params = \Symfony\Component\Yaml\Yaml::parse(file_get_contents(__DIR__.'/Symfony/app/config/parameters.yml'))['parameters'];

if ((isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], array(
        '127.0.0.1',
        '::1',
        )))
   )
{
    if (  !isset($_SERVER['PHP_AUTH_USER'])
            || !($_SERVER['PHP_AUTH_USER']==$symfony_params['dev_user'] && $_SERVER['PHP_AUTH_PW']==$symfony_params['dev_password'])
       )
    {
        header('WWW-Authenticate: Basic realm="Icse Dev"');
        header('HTTP/1.0 401 Unauthorized');
        echo "<p>{$_SERVER['REMOTE_ADDR']} is remote addr.</p>";
        echo 'You must authenticate to access this page.';
        exit;
    }
}

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
