<?php

namespace Unit\Publisher\Selector\Parameter;

use Publisher\Selector\Parameter\NullSelector;

class NullExceptionTest extends \PHPUnit_Framework_TestCase
{
    
    public function testNullObjectPattern()
    {
        $selector = new NullSelector();
        
        $this->assertSame(array(), $selector->getParameters());
        $this->assertSame(array(), $selector->getSelections());
        $this->assertFalse($selector->isParameterMissing());
        
        $selector->updateParameters(array());
    }
    
}