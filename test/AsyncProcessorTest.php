<?php

namespace App\Test;

use App\AsyncProcessor;


class AsyncProcessorTest extends \PHPUnit_Framework_TestCase
{

    public function testExecute()
    {
        $processor = new AsyncProcessor();
        $processor->execute(hash('crc32', microtime()));
    }


}