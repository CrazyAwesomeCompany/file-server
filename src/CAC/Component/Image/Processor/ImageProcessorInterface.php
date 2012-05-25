<?php

namespace CAC\Component\Image\Processor;



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


    public function crop();




}

