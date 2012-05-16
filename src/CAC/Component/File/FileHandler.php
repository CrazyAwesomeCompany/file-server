<?php

namespace CAC\Component\File;

use CAC\Component\File\Storage\FileStorageInterface;

class FileHandler
{

    /**
     * The File Storage
     *
     * @var \CAC\Component\File\Storage\FileStorageInterface
     */
    private $storage;


    public function __construct(FileStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function store($data, $filename)
    {
        return $this->storage->store($data, $filename);
    }

    /**
     * Fetch a file
     *
     * @param string $filename
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function fetch($filename)
    {
        return $this->storage->fetch($filename);
    }

}
