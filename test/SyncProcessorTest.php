<?php

namespace App\Test;

use App\SyncProcessor;


class SyncProcessorTest extends \PHPUnit_Framework_TestCase
{

    public function testExecute()
    {
        $processor = new SyncProcessor();
        $processor->execute(hash('crc32', microtime()));
    }


}