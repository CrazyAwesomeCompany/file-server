<?php

namespace CAC\Silex\Provider;


use CAC\Component\Image\Processor\GDProcessor;
use CAC\Component\Image\ImageService;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class CACImageServiceProvider implements ServiceProviderInterface
{

    /**
     * Silex Application
     *
     * @var \Silex\Application
     */
    private $app;

    public function register(Application $app)
    {
        $this->app = $app;

        // @todo Add config options!
        $app['cac.images'] = $app->share(function() use ($app) {
            if (!isset($app['cac.files'])) {
                throw new \Exception("The ImageServiceProvider needs the FileHandler service provider");
            }

            switch ($app['cac.images.processor']) {
                case 'gd':
                    $processor = new GDProcessor();

                    break;

                default:
                    throw new \Exception(sprintf("Image processor %s not supported", $app['cac.images.processor']));

                    break;
            }

            $service = new ImageService($processor, $app['cac.files']->get($app['cac.images.files.resized']), $app['cac.files']->get($app['cac.images.files.origin']));

            return $service;
        });


        $app['dispatcher']->addListener(KernelEvents::REQUEST, array($this, 'onKernelRequest'), 999);
    }


    public function onKernelRequest($event)
    {
        $request = $event->getRequest();

        if ($request->getMethod() === 'GET') {
            $requestFile = $request->getPathInfo();

            $imageService = $this->app['cac.images'];

            // Request params
            $width = $request->get('w');
            $height = $request->get('h');

            $file = $imageService->fetch($requestFile, $width, $height);

            if (!$file) {
                header("Status: 404 Not Found");
                exit;
            }

            header(sprintf('Content-type: %s', $file->getMimeType()));
            echo $file->openFile('r')->fpassthru();
            exit;
        }

    }

}