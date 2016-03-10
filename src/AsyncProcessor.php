<?php

namespace App;


class AsyncProcessor extends AbstractProcessor
{
    private $processCount = 10;
    private $rowCountInProcess;

    public function __construct()
    {
        parent::__construct();

        $this->rowCountInProcess = (int)($this->rowCount / $this->processCount);
    }

    public function execute($name)
    {
        $this->removeCsvFiles($this->tempDir);

        $processes = [];

        foreach (range(1, $this->processCount) as $processNumber)
        {
            $pid = pcntl_fork();
            if($pid === -1)
            {
                die('fork failed!');
            }
            else if ($pid)
            {
                // In parent process
                $processes[$pid] = true;
                if( count( $processes ) >= $this->processCount )
                {
                    unset( $processes[ pcntl_waitpid( -1, $status, WUNTRACED ) ] );
                }
            }
            else
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

                exit();
            }
        }
        /** Wait for all child processes */
        while( count( $processes ) > 0 )
        {
            unset( $processes[ pcntl_waitpid( -1, $status, WUNTRACED ) ] );
        }

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
