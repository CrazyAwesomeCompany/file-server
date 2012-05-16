<?php

namespace CAC\Component\File\Storage;


interface FileStorageInterface
{

    /**
     * Store an image file
     *
     * @param string $image  The image
     * @param string $name   The name of the image
     *
     * @return bool
     */
    public function store($data, $name);

    public function fetch($name);

    public function remove();



}