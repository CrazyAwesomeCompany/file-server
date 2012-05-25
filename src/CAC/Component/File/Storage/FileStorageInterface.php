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

    /**
     * Fetch an image file from the storage
     *
     * @param string $name The filename to fetch
     *
     * @return \Symfony\Component\HttpFoundation\File\File The file or NULL when file not found
     */
    public function fetch($name);

    public function remove();



}