<?php

namespace Unit\Publisher\Selector\Exception;

use Publisher\Selector\Exception\ChoiceChangedException;

class ChoiceChangedExceptionTest extends \PHPUnit_Framework_TestCase
{
    
    public function testSetStep()
    {
        $exception = new ChoiceChangedException();
        $exception->setStepId(3);
        
        $this->assertSame(3, $exception->getStepId());
    }
    
}