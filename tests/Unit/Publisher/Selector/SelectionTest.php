<?php

namespace Unit\Publisher\Selector;

use PHPUnit\Framework\TestCase;
use Publisher\Selector\Selection;

class SelectionTest extends TestCase
{
    
    public function testConstructor()
    {
        $name = 'foo';
        $choices = array('c1' => 'v1', 'c2' => 'v2', 'c3' => 'v3');
        
        $selection = new Selection($name, $choices);
        
        $this->assertEquals($name, $selection->getName());
        $this->assertEquals($choices, $selection->getChoices());
    }
    
}