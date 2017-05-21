<?php

namespace Unit\Publisher\Selector\Selection;

use Publisher\Entry\Service\Selector\ServicePageSelectorDefinition;
use Publisher\Selector\Selection\SelectionCollection;
use Publisher\Requestor\Request;

/**
 * Class to test the implementations of the class
 * Publisher\Selector\Selection\SelectorDefinition
 * with the Mock Class
 * Publisher\Entry\Service\Selection\ServicePageSelectorDefinition.
 */
class SelectorDefinitionTest extends \PHPUnit_Framework_TestCase
{
    
    public function testGetRequest()
    {
        $selectorDefinition = new ServicePageSelectorDefinition();
        
        $selectionCollection = new SelectionCollection();
        
        $this->assertInstanceOf(
           Request::class,
           $selectorDefinition->getRequest($selectionCollection)
        );
        
        $selectionCollection->makeDecision('param1', 'decision1');
        $this->assertInstanceOf(
           Request::class,
           $selectorDefinition->getRequest($selectionCollection)
        );
        
        $selectionCollection->makeDecision('param2', 'decision2');
        // ServicePageSelectorDefinition defines only two steps.
        $this->assertNull(
            $selectorDefinition->getRequest($selectionCollection)
        );
    }
    
}
