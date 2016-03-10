<?php

namespace App;


class AsyncProcessor extends AbstractParallelProcessor
{

    protected function beforeProcess($name)
    {
        $this->removeCsvFiles($this->tempDir);
    }

    protected function process($name, $processNumber)
    {
        $path = $this->buildFilePath($name, '_'. sprintf("%02d", $processNumber));
        $fp = fopen($path, 'a');

        $startRowNumber = ($processNumber - 1) * ($this->rowCountInProcess) + 1;
        $endRowNumber = $startRowNumber + ($this->rowCountInProcess) - 1;
        for ($rowNumber = $startRowNumber; $rowNumber <= $endRowNumber; $rowNumber++)
        {
            $data = $this->createData($rowNumber);
            fputcsv($fp, $data);
        }

        fclose($fp);
    }

    protected function afterProcess($name)
    {
        $this->mergeCsvFiles($name);
    }

    private function mergeCsvFiles($name)
    {
        $merged = $this->buildFilePath($name);
        $fp = fopen($merged, 'a');

        $path = $this->buildFilePath($name, '_*');
        $children = glob($path);
        foreach ($children as $file)
        {
            fputs($fp, file_get_contents($file));
            unlink($file);
        }

        fclose($fp);
    }

}
