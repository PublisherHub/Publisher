<?php

namespace Unit\Publisher\Selector\Selection;

use PHPUnit\Framework\TestCase;
use Publisher\Selector\Selection\SelectionCollection;
use Publisher\Selector\Selection;

class SelectionCollectionTest extends TestCase
{
    
    public function testDefaultConstructor()
    {
        $selectionCollection = new SelectionCollection();
        
        $this->assertEquals([], $selectionCollection->getDecisions());
        $this->assertEquals([], $selectionCollection->getSelections());
        $this->assertSame(0, $selectionCollection->getCurrentStepId());
    }
    
    public function testMainActions()
    {
        $decisions = ['param1' => 'decision1'];
        $selections = [$this->createMock(Selection::class), $this->createMock(Selection::class)];
        
        $selectionCollection = new SelectionCollection($decisions, $selections);
        
        $this->assertEquals($decisions, $selectionCollection->getDecisions());
        $this->assertEquals($selections, $selectionCollection->getSelections());
        $this->assertSame(1, $selectionCollection->getCurrentStepId());
        
        // make decision
        $this->assertFalse($selectionCollection->hasDecided('param2'));
        
        $selectionCollection->makeDecision('param2', 'decision2');
        $this->assertTrue($selectionCollection->hasDecided('param2'));
        // - the stepId should have increased by one
        $this->assertSame(2, $selectionCollection->getCurrentStepId());
        
        $savedDecisions = $selectionCollection->getDecisions();
        $this->assertArrayHasKey('param1', $savedDecisions);
        $this->assertArrayHasKey('param2', $savedDecisions);
        
        // add Selection
        $selectionCollection->addSelection(
            'param3',
            ['option1' => 'value1', 'option1' => 'value1']
        );
        // - the stepId shouldn't have increased
        $this->assertSame(2, $selectionCollection->getCurrentStepId());
        $savedSelections = $selectionCollection->getSelections();
        $this->assertEquals(3, count($savedSelections));
        $this->assertEquals('param3', $savedSelections[2]->getName());
    }
    
    public function testResetDecisionsAndSelections()
    {
        $decisions = ['param1' => 'decision1', 'param2' => 'decision2'];
        $selections = [
            new Selection('param1'),
            new Selection('param2'),
            new Selection('param3')
        ];
        
        $selectionCollection = new SelectionCollection($decisions, $selections);
        
        $this->assertEquals(2, $selectionCollection->getCurrentStepId());
        $selectionCollection->makeDecision('param1', 'changedMyMind');
        
        $this->assertEquals(1, $selectionCollection->getCurrentStepId());
        $this->assertNotContains('param2', $selectionCollection->getDecisions());
        $savedSelections = $selectionCollection->getSelections();
        $this->assertEquals(1, count($savedSelections));
        $this->assertEquals('param1', $savedSelections[0]->getName());
    }
    
}
