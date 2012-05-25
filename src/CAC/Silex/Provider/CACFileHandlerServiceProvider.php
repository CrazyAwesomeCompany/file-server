<?php

namespace CAC\Silex\Provider;


use Silex\Application;
use Silex\ServiceProviderInterface;

class CACFileHandlerServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        // @todo Add config options!
        // @todo Allow multiple filehandlers
        $app['cac.files'] = $app->share(function() use ($app) {
            $storage = new \CAC\Component\File\Storage\FileSystemStorage(__DIR__ . '/../../../../tmp');
            $fileHandler = new \CAC\Component\File\FileHandler($storage);

            return $fileHandler;
        });

    }

}