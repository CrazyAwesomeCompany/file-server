<?php

namespace CAC\Silex\Provider;


use CAC\Component\File\FileService;

use Silex\Application;
use Silex\ServiceProviderInterface;

/*
 * Config example:
 *
array(
    'cac.files.handlers' => array(
        'default' => array(
            'type' => 'file',
            'path' => '/path/to/folder'
        )
    ),
    'cac.files.handlers.default' => 'default'
);
*/

class CACFileHandlerServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        if (!isset($app['cac.files.handlers']) || !is_array($app['cac.files.handlers'])) {
            throw new \Exception("You need to specify at least one file handler");
        }

        // @todo Allow multiple filehandlers
        $app['cac.files'] = $app->share(function() use ($app) {
            $service = new FileService();

            foreach ($app['cac.files.handlers'] as $name => $handler) {
                switch ($handler['type']) {
                    case 'file':
                        $storage = new \CAC\Component\File\Storage\FileSystemStorage($handler['path']);

                        break;

                    default:
                        throw new \Exception(sprintf("Storage type %s not (yet) supported", $handler['type']));

                        break;
                }

                $fileHandler = new \CAC\Component\File\FileHandler($storage);

                // Add the handler to the service
                $service->addHandler($name, $fileHandler);
            }

            $service->setDefaultHandler($app['cac.files.handlers.default']);

            return $service;
        });

    }

}