<?php
namespace CAC\Silex;
use Silex\ControllerCollection;

use Silex\ControllerProviderInterface;

class CACImageController implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controllers = new ControllerCollection();

        $controllers->post('/upload', function(Request $request) use ($app) {
            // Let's upload an image
            $imageName = $request->get('name');
            $imageData = $request->get('data');

            $app['cac.images']->store(base64_decode($imageData), $imageName);
        });

        return $controllers;
    }

}
