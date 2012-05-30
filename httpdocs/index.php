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
            ),
            'origin' => array(
                'type' => 'file',
                'path' => __DIR__ . '/../tmp/origin'
            )
        ),
        'cac.files.handlers.default' => 'default'
    ),
    'images' => array(
        'cac.images.processor' => 'gd',
        'cac.images.files.resized' => 'default',
        'cac.images.files.origin' => 'origin'
    )
);


$app = new Silex\Application();
$app['debug'] = $config['debug'];

$app->register(new \CAC\Silex\Provider\CACFileHandlerServiceProvider(), $config['files']);
$app->register(new \CAC\Silex\Provider\CACImageServiceProvider(), $config['images']);


$app->get('/', function() use ($app) {
    return 'IMG API!';
});

$app->get('/test', function() use ($app) {
    $imagedata = file_get_contents(__DIR__ . '/../tmp/nick-cropped.jpg');
    $imagename = 'user/1/profile/profileimage.jpg';

    $app['cac.images']->store($imagedata, $imagename);
});

$app->post('/upload', function(Request $request) use ($app) {
    // Let's upload an image
    $imageName = $request->get('name');
    $imageData = $request->get('data');

    $app['cac.images']->store(base64_decode($imageData), $imageName);
});

$app->run();
