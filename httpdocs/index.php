<?php

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Application config
 */
$config = array(
    'debug' => true,

    'files' => array(
        'cac.files.handlers' => array(
            'default' => array(
                'type' => 'file',
                'path' => __DIR__ . '/../tmp'
            )
        ),
        'cac.files.handlers.default' => 'default'
    ),
    'images' => array(
        'cac.images.processor' => 'gd',
        'cac.images.files.resized' => 'default',
        'cac.images.files.origin' => 'default'
    )
);


$app = new Silex\Application();
$app['debug'] = $config['debug'];

$app->register(new \CAC\Silex\Provider\CACFileHandlerServiceProvider(), $config['files']);
$app->register(new \CAC\Silex\Provider\CACImageServiceProvider(), $config['images']);


$app->get('/', function() use ($app) {
    return 'IMG API!';
});


$app->run();