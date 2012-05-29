<?php

namespace CAC\Silex\Provider;


use CAC\Component\File\FileService;

use Silex\Application;
use Silex\ServiceProviderInterface;

class CACFileHandlerServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        // @todo Add config options!
        // @todo Allow multiple filehandlers
        $app['cac.files'] = $app->share(function() use ($app) {
            $service = new FileService();

            $storage = new \CAC\Component\File\Storage\FileSystemStorage(__DIR__ . '/../../../../tmp');
            $fileHandler = new \CAC\Component\File\FileHandler($storage);

            $service->addHandler('default', $fileHandler);
            $service->setDefaultHandler('default');

            return $service;
        });

    }

}