<?php

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->before(function(Request $request) use ($app) {
    if ($request->getMethod() === 'GET') {
        $requestFile = $request->getPathInfo();

        $storage = new \CAC\Component\File\Storage\FileSystemStorage(__DIR__ . '/../tmp');
        $fileHandler = new \CAC\Component\File\FileHandler($storage);

        $file = $fileHandler->fetch($requestFile);

        header(sprintf('Content-type: %s', $file->getMimeType()));
        echo $file->openFile('r')->fpassthru();
        exit;
    }




    //$fileData = file_get_contents(__DIR__ . '/screenshot-ibstudent.png');


    //$fileHandler->store($fileData, '/test/../../?sp    LALA&-data.png');

    echo $requestFile;


    echo "Before shizzle";



    //return $app->redirect("/");

});

$app->get('/', function() use ($app) {
    return 'IMG API!';
});


$app->run();