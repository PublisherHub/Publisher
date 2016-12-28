<?php

namespace Unit\Publisher\Selector\Manager;

use Publisher\Selector\Manager\SelectorManager;
use Publisher\Selector\Factory\SelectorFactoryInterface;
use Publisher\Selector\Selection;

class SelectorManagerTest extends \PHPUnit_Framework_TestCase
{
    
    public function testUpdateSelectors()
    {
        $this->selectors = array(
            'ServiceForum' => $this->getSelectorsThatUpdateParameters(),
            'ServicePage' => $this->getSelectorsThatUpdateParameters()
        ); 
        
        $selectorManager = $this->getSelectorManager(
                $this->getSelectorFactory(),
                array_keys($this->selectors)
        );
        
        $choices = array(
            'ServiceForum' => array(
                'forum' => 'foo',
            ),
            'ServicePage' => array(
                'firstParameter' => 'first',
                'secondParameter' => 'second',
            )
        );
        
        $selectorManager->updateSelectors($choices);
    }
    
    /**
     * @dataProvider getSelectionsForTwoSelectors
     * 
     * @param array $selections1
     * @param array $selections2
     */
    public function testGetSelectors(array $selections1, array $selections2)
    {
        $this->selectors = array(
            'ServiceForum' => $this->getSelectorThatHasSelections($selections1),
            'ServicePage' => $this->getSelectorThatHasSelections($selections2)
        );
        
        $selectorManager = $this->getSelectorManager(
                $this->getSelectorFactory(),
                array_keys($this->selectors)
        );
        
        $allSelections = array(
            'ServiceForum' => $selections1,
            'ServicePage' => $selections2
        );
        
        $this->assertEquals(
                $allSelections,
                $selectorManager->getSelections()
        );
    }
    
    /**
     * @dataProvider getSelectionsForTwoSelectors
     * 
     * @param array $selections1
     * @param array $selections2
     */
    public function testGetSelectorsAsArray(array $selections1, array $selections2)
    {
        $this->selectors = array(
            'ServiceForum' => $this->getSelectorThatHasSelections($selections1),
            'ServicePage' => $this->getSelectorThatHasSelections($selections2)
        );
        
        $selectorManager = $this->getSelectorManager(
                $this->getSelectorFactory(),
                array_keys($this->selectors)
        );
        
        $allSelectionsAsArray = array(
            'ServiceForum' => array(
                '0' => array(
                    'name' => 'forum', 
                    'choices' => array('1' => 'foo', '2' => 'bar')
                )
            ),
            'ServicePage' => array(
                '0' => array(
                    'name' => 'firstParameter',
                    'choices' => array('1' => 'a', '2' => 'b')
                ),
                '1' => array(
                    'name' => 'secondParameter',
                    'choices' => array('f' => 'foo', 'b' => 'bar')
                )
            )
        );
        
        $this->assertEquals(
                $allSelectionsAsArray,
                $selectorManager->getSelectionsAsArray()
        );
    }
    
    public function getSelectionsForTwoSelectors()
    {
        $selections1 = array(
            new Selection('forum', array('1' => 'foo', '2' => 'bar'))
        );
        $selections2 = array(
            new Selection('firstParameter', array('1' => 'a', '2' => 'b')),
            new Selection('secondParameter', array('f' => 'foo', 'b' => 'bar'))
        );
        
        return array(array($selections1, $selections2));
    }
    
    protected function getSelectorManager(
            SelectorFactoryInterface $selectorFactory,
            array $entryIds
    ) {
        $selectorManager = new SelectorManager($selectorFactory);
        $selectorManager->setupSelectors($entryIds);
        
        return $selectorManager;
    }
    
    protected function getSelectorsThatUpdateParameters()
    {
        $selector = $this->getSelectorMock();
        $selector->expects($this->once())
            ->method('updateParameters');
        
        return $selector;
    }
    
    protected function getSelectorThatHasSelections(array $selections)
    {
        $selector = $this->getSelectorMock();
        $selector->expects($this->once())
            ->method('getSelections')
            ->willReturn($selections);
        
        return $selector;
    }
    
    protected function getSelectorMock()
    {
        $interface = 'Publisher\Selector\Parameter\SelectorInterface';
        
        return $this->getMock($interface);
    }
    
    protected function getSelectorFactory()
    {
        $interface = 'Publisher\Selector\Factory\SelectorFactoryInterface';
        $factory = $this->getMock($interface);
        
        $factory->expects($this->any())
                ->method('create')
                ->will($this->returnCallback(array($this, 'createCallback')));
        
        return $factory;
    }
    
    public function createCallback($entryId) {
        return $this->selectors[$entryId];
    }
}