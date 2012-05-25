<?php

namespace CAC\Component\Image\Processor;


use Symfony\Component\HttpFoundation\File\File;

class GDProcessor implements ImageProcessorInterface
{

    public function crop()
    {


    }


    /**
     * (non-PHPdoc)
     * @see \CAC\Component\Image\Processor\ImageProcessorInterface::resize()
     */
    public function resize($image, $width, $height, $keepAspect = true)
    {
        $image = $this->getImage($image);

        if ($keepAspect) {
            // Calculate the resize factors
            $factX = $width  / imagesx($image);
            $factY = $height / imagesy($image);
            // We need the smallest factor
            $factor = ($factY < $factX) ? $factY : $factX;
            // New image size
            $newX = round(imagesx($image) * $factor);
            $newY = round(imagesy($image) * $factor);
        } else {
            $newX = $width;
            $newY = $height;
        }

        $newImage = $this->createAlphaImage($newX, $newY);
        $result = imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newX, $newY, imagesx($image), imagesy($image));

        if ($result) {
            // Grab the image resource
            ob_start();
            imagepng($newImage);
            imagedestroy($newImage);
            $data = ob_get_contents();
            ob_end_clean();

            return $data;
        }

        return false;
    }

    /**
     * Create a new image and add transparancy when possible
     *
     * @param integer  $newX The image width
     * @param integer  $newY The image height
     * @param integer  $type The image type (\IMAGETYPE_PNG or \IMAGETYPE_GIF) used to determine transparancy
     * @param resource $originalImage The original image, only used when image type is GIF
     *
     * @return resource The new image
     */
    private function createAlphaImage($width, $height, $type = null, $originalImage = null)
    {
        $image = imagecreatetruecolor($width, $height);
        // maintain alpha for gif and png
        if ($type == \IMAGETYPE_PNG) {
            imagealphablending($image, false);
            imagesavealpha($image, true);
            $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
            imagefilledrectangle($image, 0, 0, $width, $height, $transparent);

        } elseif ($type == \IMAGETYPE_GIF) {
            $trnprt_indx = imagecolortransparent($originalImage);

            if ($trnprt_indx >= 0) {
                //its transparent
                $trnprt_color = @imagecolorsforindex($originalImage, $trnprt_indx);
                $trnprt_indx  = imagecolorallocate($image, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
                imagefill($image, 0, 0, $trnprt_indx);
                imagecolortransparent($image, $trnprt_indx);
            }
        }

        return $image;
    }


    /**
     * Get the image resource from the given image
     *
     * @param mixed $image
     *
     * @return resource
     */
    public function getImage($image)
    {
        if (is_resource($image)) {
            return $image;
        } elseif (is_string($image)) {
            return imagecreatefromstring($image);
        } elseif ($image instanceof File) {
            ob_start();
            $image->openFile('r')->fpassthru();
            $data = ob_get_contents();
            ob_end_clean();

            $ext = $image->guessExtension();

            $function = 'imagecreatefrom' . $ext;
            if (function_exists($function)) {
                return $function($image);
            }
        }
    }








}