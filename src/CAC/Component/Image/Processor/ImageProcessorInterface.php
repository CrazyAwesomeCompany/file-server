<?php

namespace CAC\Component\Image\Processor;

/**
 * Image Processor
 *
 * Adjust original image files to the given parameters
 *
 * @author Nick de Groot <nick@crazyawesomecompany.com>
 */
interface ImageProcessorInterface
{

    /**
     * Resize an image to the given dimensions
     *
     * @param resource|string $image      The original image
     * @param integer         $width      New width of the image
     * @param integer         $height     New height of the image
     * @param bool            $keepAspect Keep the original aspect ratio of the image
     *
     * @return string The new image or FALSE when new image fails
     */
    public function resize($image, $width, $height, $keepAspect = true);

    /**
     * Resize and crop an image to the given dimensions
     *
     * @param resource|string $image  The original image
     * @param integer         $width  New width of the image
     * @param integer         $height New height of the image
     *
     * @return string The new image or FALSE when new image fails
     */
    public function crop($image, $width, $height);

}

