<?php

namespace CAC\Component\File\Storage;

use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

/**
 * FileSystem Storage
 *
 * This class stores files on the file system
 *
 * @author Nick de Groot <nick@crazyawesomecompany.com>
 */
class FileSystemStorage implements FileStorageInterface
{

    /**
     * Root directory
     *
     * @var string
     */
    private $storageDirectory = "C:\\Web\\Temp\\images";

    //private $imagesPerDirectory = 1500;

    public function __construct($storageDirectory)
    {
        $this->storageDirectory = $storageDirectory;
    }

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\File\Storage\FileStorageInterface::store()
     */
    public function store($data, $name)
    {
        $name = $this->sanatizeName($name);

        // Check if we have to create directories
        $pos = strrpos($name, "/");
        if ($pos > 0) {
            // Directories in name.. Create the directories
            $directories = substr($name, 0, $pos);
            $this->createDirectory($directories);
        }

        try {
            $handle = fopen($this->storageDirectory . DIRECTORY_SEPARATOR . $name, 'w');
            fwrite($handle, $data);
            fclose($handle);
        } catch (\Exception $e) {
            throw new StorageException(
                $e->getMessage()
            );
        }
    }

    public function sanatizeName($name)
    {
        // Replace directory statements
        $replace = array("../", "./");
        $name = str_replace($replace, "", $name);

        // Replace special characters
        $replace = array(" ", "?", "@", "!", "&");
        $name = str_replace($replace, "_", $name);

        return $name;
    }

    /**
     * (non-PHPdoc)
     * @see \Liveat\Component\Image\Storage\ImageStorageInterface::fetch()
     */
    public function fetch($name)
    {
        $name = $this->sanatizeName($name);

        $filename = $this->storageDirectory . DIRECTORY_SEPARATOR . $name;

        return new File($filename);
    }

    /**
     * (non-PHPdoc)
     * @see \Liveat\Component\Image\Storage\ImageStorageInterface::remove()
     */
    public function remove()
    {

    }


    /**
     * Get the storage directory for a given bucket
     *
     * @param string $bucket The bucket name
     *
     * @return string The full path
     */
    protected function getStoragePath($bucket)
    {
        // Get the last directory
        $lastDirectory = $this->getLastDirectory($bucket);

        $lastDirectoryPath = $this->imageDirectory . DIRECTORY_SEPARATOR . $bucket . DIRECTORY_SEPARATOR . $lastDirectory;
        $imageCount = $this->getDirectoryImageCount($lastDirectoryPath);

        if ($imageCount >= $this->imagesPerDirectory) {
            // Create a new directory
            $lastDirectory = sprintf("%03s", ($lastDirectory + 1));
            $this->createDirectory($bucket . DIRECTORY_SEPARATOR . $lastDirectory);
        }

        return $this->imageDirectory . DIRECTORY_SEPARATOR . $bucket . DIRECTORY_SEPARATOR . $lastDirectory;
    }

    /**
     * Create the directory if it's not yet available on the File System
     *
     * @param string $path     Directory path name
     * @param string $root     Starting directory, when not given it will use the default root dir
     * @param bool   $absolute Use path as an absolute directory
     *
     * @throws StorageException Unable to create directory
     *
     * @return boolean
     */
    protected function createDirectory($path, $root = null, $absolute = false)
    {
        if (!$root && !$absolute) {
            $pathname = $this->storageDirectory . DIRECTORY_SEPARATOR . $path;
        } else if ($absolute) {
            $pathname = $path;
        } else {
            $pathname = $root . DIRECTORY_SEPARATOR . $path;
        }

        if (!is_dir($pathname)) {
            if (!mkdir($pathname, 0775, true)) {
                throw new StorageException(
                    sprintf("Unable to create directory: %s", $pathname)
                );
            }
        }

        return true;
    }

}
