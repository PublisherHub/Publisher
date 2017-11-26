<?php

namespace Unit\Publisher\Selector;

use PHPUnit\Framework\TestCase;
use Publisher\Selector\Selector;
use Publisher\Requestor\RequestorInterface;
use Publisher\Selector\Selection\SelectorDefinition;
use Publisher\Selector\Selection\SelectionCollection;
use Publisher\Requestor\Request;

class SelectorTest extends TestCase
{
    
    public function testIsParameterMissing()
    {
        $selectorDefinition = $this->createMock(SelectorDefinition::class);
        $selectorDefinition->expects($this->once())
            ->method('isDecisionMissing')
            ->with([])
            ->willReturn(true);
        
        $selector = new Selector(
            $this->createMock(RequestorInterface::class),
            $selectorDefinition,
            new SelectionCollection()
        );
        
        $this->assertTrue($selector->isParameterMissing());
    }
    
    /**
     * @dataProvider getTestDataForUpdateParameters
     * 
     * @param array $givenDecisions
     * @param array $savedDecisions
     */
    public function testUpdateParameters(array $givenDecisions, array $savedDecisions)
    {
        $selectorDefinition = $this->createMock(SelectorDefinition::class);
        $selectorDefinition->expects($this->any())
            ->method('getDecisionOrder')
            ->willReturn(['param1', 'param2', 'param3']);
        
        $selector = new Selector(
            $this->createMock(RequestorInterface::class),
            $selectorDefinition,
            new SelectionCollection()
        );
        
        $selector->updateParameters($givenDecisions);
        
        $this->assertEquals(
            $savedDecisions,
            $selector->getCollection()->getDecisions()
        );
    }
    
    public function getTestDataForUpdateParameters()
    {
        // given decisions, saved decisions
        return [
            'in_progress' => [
                ['param1' => '1', 'param2' => 2],
                ['param1' => '1', 'param2' => 2],
            ],
            'all_parameters_set' => [
                ['param1' => '1', 'param3' => 2, 'param2' => '4'],
                ['param1' => '1', 'param3' => 2, 'param2' => '4'],
            ],
            'ignore_additional_parameters' => [
                ['param1' => '1', 'param2' => 2, 'param4' => '4'],
                ['param1' => '1', 'param2' => 2],
            ]
        ];
    }
    
    public function testExecuteCurrentStep()
    {
        $selectionCollection = new SelectionCollection();
        
        $selectorDefinition = $this->createMock(SelectorDefinition::class);
        $request = new Request('/');
        $selectorDefinition->expects($this->once())
            ->method('getRequest')
            ->with($selectionCollection)
            ->willReturn($request);
        
        $requestor = $this->createMock(RequestorInterface::class);
        $response = '{"foo": "bar"}';
        $requestor->expects($this->once())
            ->method('doRequest')
            ->with($request)
            ->willReturn($response);
        
        $selectorDefinition->expects($this->once())
            ->method('updateDecisions')
            ->with($selectionCollection, $response);
        
        $selector = new Selector(
            $requestor,
            $selectorDefinition,
            $selectionCollection
        );
        
        $selector->executeCurrentStep();
    }
    
    public function testGetParameters()
    {
        $decisions = ['param' => 'decisions'];
        $selectionCollection = new SelectionCollection($decisions);
        
        $selectorDefinition = $this->createMock(SelectorDefinition::class);
        $selectorDefinition->expects($this->once())
            ->method('getRequiredParameters')
            ->with($selectionCollection->getDecisions())
            ->willReturn($selectionCollection->getDecisions());
        
        $selector = new Selector(
            $this->createMock(RequestorInterface::class),
            $selectorDefinition,
            $selectionCollection
        );
        
        $this->assertEquals($decisions, $selector->getParameters());
    }
    
}
