<?php

namespace Liveat\Component\Image\Processor;



interface ImageProcessorInterface
{

    public function resize($image, $width, $height);


    public function crop();




}

