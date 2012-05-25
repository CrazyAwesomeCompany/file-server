<?php

namespace CAC\Component\File;


class FileService
{

    private $handlers = array();


    private $defaultHandler;


    /**
     * Get a FileHandler
     *
     * @param string $key
     *
     * @return \CAC\Component\File\FileHandler
     */
    public function get($key = null)
    {
        if (!$key) {
            $key = $this->defaultHandler;
        }

        return $this->handlers[$key];
    }

    /**
     * Add a FileHandler
     *
     * @param string      $key     Name of the file handler
     * @param FileHandler $handler The handler
     */
    public function addHandler($key, FileHandler $handler)
    {
        $this->handlers[$key] = $handler;

        return $this;
    }

    public function setDefaultHandler($key)
    {
        $this->defaultHandler = $key;

        return $this;
    }


}