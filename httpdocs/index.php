<?php

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new \CAC\Silex\Provider\CACFileHandlerServiceProvider());


$app->before(function(Request $request) use ($app) {
    if ($request->getMethod() === 'GET') {
        $requestFile = $request->getPathInfo();

        $fileHandler = $app['cac.files'];

        $storage2 = new \CAC\Component\File\Storage\FileSystemStorage(__DIR__ . '/../tmp/resized');
        $fileHandler2 = new \CAC\Component\File\FileHandler($storage2);

        $imageProcessor = new \CAC\Component\Image\Processor\GDProcessor();

        $imageService = new \CAC\Component\Image\ImageService($imageProcessor, $fileHandler2, $fileHandler);


        // Request params
        $width = $request->get('w');
        $height = $request->get('h');

        $file = $imageService->fetch($requestFile, $width, $height);

        header(sprintf('Content-type: %s', $file->getMimeType()));
        echo $file->openFile('r')->fpassthru();
        exit;
    }

    echo $requestFile;


    echo "Before shizzle";



    //return $app->redirect("/");

});

$app->get('/', function() use ($app) {
    return 'IMG API!';
});


$app->run();