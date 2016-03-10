<?php

namespace App;


interface ProcessorInterface
{
    /**
     * @param $name string
     */
    public function execute($name);

}