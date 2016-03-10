<?php

namespace App;


abstract class AbstractParallelProcessor extends AbstractProcessor
{
    protected $processCount = 10;
    protected $rowCountInProcess;

    public function __construct()
    {
        parent::__construct();

        $this->rowCountInProcess = (int)($this->rowCount / $this->processCount);
    }

    public function execute($name)
    {
        $this->beforeProcess($name);

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
                $this->process($name, $processNumber);
                exit();
            }
        }
        /** Wait for all child processes */
        while( count( $processes ) > 0 )
        {
            unset( $processes[ pcntl_waitpid( -1, $status, WUNTRACED ) ] );
        }

        $this->afterProcess($name);
    }

    abstract protected function beforeProcess($name);

    abstract protected function process($name, $processNumber);

    abstract protected function afterProcess($name);

}
