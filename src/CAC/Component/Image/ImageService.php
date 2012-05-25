<?php

namespace CAC\Component\Image;


class ImageService
{

    /**
     * The File Storage Handler
     *
     * @var \CAC\Component\File\FileHandler
     */
    private $fileHandler;

    /**
     * The File Storage Handler for the original files
     *
     * @var \CAC\Component\File\FileHandler
     */
    private $fileHandlerOrigin;

    /**
     * The Image Processor
     *
     * @var \CAC\Component\Image\Processor\ImageProcessorInterface
     */
    private $processor;


    public function __construct($processor, $fileHandler, $fileHandlerOrigin = null)
    {
        $this->processor = $processor;
        $this->fileHandler = $this->fileHandlerOrigin = $fileHandler;
        if ($fileHandlerOrigin) {
            $this->fileHandlerOrigin = $fileHandlerOrigin;
        }
    }

    /**
     * Fetch an Image
     *
     * @param string  $filename
     * @param integer $height
     * @param integer $width
     * @param string  $resizeType
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function fetch($filename, $height = null, $width = null, $resizeType = 'resize')
    {
        if (null !== $height || null !== $height) {
            return $this->fetchResized($filename, $height, $width, $resizeType);
        }

        return $this->fileHandler->fetch($filename);
    }

    /**
     * Fetch a resized image from the storage, when it not exists generate the resized one
     *
     * @param string  $filename Image filename
     * @param integer $width    Resized image width
     * @param integer $height   Resized image height
     * @param string  $resizeType Type of resizing (resize, crop)
     *
     * @throws ImageException
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function fetchResized($filename, $width, $height, $resizeType)
    {
        if (!(intval($height) > 0 && intval($width) > 0)) {
            throw new ImageException(
                "No valid height or width given"
            );
        }

        // @todo Add Maximum size checks

        // Get the filename of the resized image
        $resizedFilename = $this->getResizedFilename($filename, $width, $height, $resizeType);
        // Try to get the resized image from the file storage
        $file = $this->fileHandler->fetch($resizedFilename);

        if (null !== $file) {
            // File found.. return it
            return $file;
        }

        // Resized file not found.. fetch the original image
        $file = $this->fileHandlerOrigin->fetch($filename);

        if (null === $file) {
            throw new ImageException(
                "Can not find original file to do resizing"
            );
        }

        switch ($resizeType) {
            case 'resize':
                // Resize with aspect ratio
                $image = $this->processor->resize($file, $width, $height, true);

                break;

            default:
                throw new ImageException(
                    "Resize function not (yet) implemented"
                );

                break;
        }

        if (!$image) {
            throw new ImageException("Resizing of image failed");
        }

        // Store the image
        $this->fileHandler->store($image, $resizedFilename);

        return $this->fileHandler->fetch($resizedFilename);
    }

    /**
     * Get the filename for the resized image
     *
     * @param string  $filename   The original filename
     * @param integer $width      The resized image width
     * @param integer $height     The resized image height
     * @param string  $resizeType The resize type
     *
     * @return string The filename of the resized image
     */
    protected function getResizedFilename($filename, $width, $height, $resizeType)
    {
        $posFileDir = strrpos($filename, '/');
        $posExt = strrpos($filename, '.');
        // define folder, filename and extension
        $dir = substr($filename, 0, $posFileDir + 1);
        $name = substr($filename, $posFileDir + 1, $posExt - $posFileDir - 1);
        $ext = substr($filename, $posExt + 1);

        // Build the resized file name
        $resizedFilename = $name . '_' . $width . 'x' . $height . '-' . $resizeType;
        $resizedFilename .= '.' . $ext;

        return $resizedFilename;
    }

}




