<?php

namespace Unit\Publisher\Selector\Exception;

use PHPUnit\Framework\TestCase;
use Publisher\Selector\Exception\ChoiceChangedException;

class ChoiceChangedExceptionTest extends TestCase
{
    
    public function testSetStep()
    {
        $exception = new ChoiceChangedException();
        $exception->setStepId(3);
        
        $this->assertSame(3, $exception->getStepId());
    }
    
}