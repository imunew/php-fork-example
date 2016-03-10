<?php

namespace App;


class AbstractProcessor
{
    protected $rowCount = 10000;
    protected $columnCount = 100;
    protected $tempDir;

    public function __construct()
    {
        $this->tempDir = realpath(__DIR__. '/../var/temp');
    }

    protected function buildFilePath($name, $suffix = '')
    {
        $fileName = $name. $suffix. '.csv';
        return implode(DIRECTORY_SEPARATOR, [$this->tempDir, $fileName]);
    }

    protected function createData($rowNumber)
    {
        $data = [];

        foreach (range(1, $this->columnCount) as $columnNumber)
        {
            $data[] = ($rowNumber - 1) * $this->columnCount + $columnNumber;
            usleep(10);
        }

        return $data;
    }

    protected function removeCsvFiles($dir)
    {
        foreach (glob($dir.'/*.csv') as $file)
        {
            unlink($file);
        }
    }

}