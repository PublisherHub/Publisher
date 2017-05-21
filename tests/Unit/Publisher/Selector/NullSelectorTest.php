<?php

namespace Unit\Publisher\Selector;

use Publisher\Selector\NullSelector;
use Publisher\Selector\Selection\SelectionCollectionInterface;
use Publisher\Selector\SelectorInterface;

class NullSelectorTest extends \PHPUnit_Framework_TestCase
{
    
    public function testBehaviour()
    {
        $selector = new NullSelector();
        
        $this->assertFalse($selector->isParameterMissing());
        $this->assertEquals([], $selector->getParameters());
        $this->confirmCollectionIsEmpty($selector);
        
        // confirm that other methods don't change the values
        $selector->updateParameters(['param1' => 'decision1']);
        $this->confirmCollectionIsEmpty($selector);
        
        $selector->executeCurrentStep();
        $this->confirmCollectionIsEmpty($selector);
    }
    
    protected function confirmCollectionIsEmpty(SelectorInterface $selector)
    {
        $selectionCollection = $selector->getCollection();
        $this->assertInstanceOf(SelectionCollectionInterface::class, $selectionCollection);
        $this->assertSame(0, $selectionCollection->getCurrentStepId());
        $this->assertEquals([], $selectionCollection->getDecisions());
        $this->assertEquals([], $selectionCollection->getSelections());
    }
}
