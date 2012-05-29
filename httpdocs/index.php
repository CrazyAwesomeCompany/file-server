<?php

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new \CAC\Silex\Provider\CACFileHandlerServiceProvider());
$app->register(new \CAC\Silex\Provider\CACImageServiceProvider());


$app->get('/', function() use ($app) {
    return 'IMG API!';
});


$app->run();