<?php

namespace App;


class SyncProcessor extends AbstractProcessor
{

    public function execute($name)
    {
        $path = $this->buildFilePath($name);
        $this->removeCsvFiles(dirname($path));

        $fp = fopen($path, 'a');

        foreach (range(1, $this->rowCount) as $rowNumber)
        {
            $data = $this->createData($rowNumber);
            fputcsv($fp, $data);
        }

        fclose($fp);
    }

}
