<?php

namespace Unit\Publisher\Selector\Manager;

use PHPUnit\Framework\TestCase;
use Publisher\Selector\Manager\SelectorManager;
use Publisher\Selector\Factory\SelectorFactoryInterface;
use Publisher\Selector\Selection;
use Publisher\Selector\SelectorInterface;
use Publisher\Selector\Selection\SelectionCollection;
use Publisher\Selector\Selection\SelectionCollectionInterface;
use Publisher\Selector\Factory\SelectionCollectionArrayTransformerInterface;

class SelectorManagerTest extends TestCase
{
    
    /**
     * Can be defined before each test.
     * 
     * @var SelectorInterface[]
     */
    protected $selectors;


    public function testDefaults()
    {
        $selectorManager = $this->getSelectorManager($this->getSelectorFactory());
        
        $this->assertTrue($selectorManager->areAllParametersSet());
        $this->assertEquals([], $selectorManager->getCollections());
        $this->assertEquals([], $selectorManager->getParameters());
    }
    
    public function testRecoverSelectionCollections()
    {
        $this->selectors = array(
            'ServiceForum' => $this->getSelectorMock(),
            'ServicePage' => $this->getSelectorMock()
        );
        
        $selectorManager = $this->getSelectorManager($this->getSelectorFactory());
        
        $decisions = ['foo' => 'bar'];
        $selectionCollections = [
            'ServicePage' => ['decisions' => $decisions]
        ];
        $selectorManager->setupSelectors(array_keys($this->selectors), $selectionCollections);
        
        $selectionCollections = $selectorManager->getCollections();
        $this->assertEquals([], $selectionCollections['ServiceForum']->getDecisions());
        $this->assertEquals($decisions, $selectionCollections['ServicePage']->getDecisions());
    }
    
    public function testUpdateSelectors()
    {
        $this->selectors = array(
            'ServiceForum' => $this->getSelectorsThatUpdateParameters(),
            'ServicePage' => $this->getSelectorsThatUpdateParameters()
        );
        
        $selectorManager = $this->getSelectorManager($this->getSelectorFactory());
        $selectorManager->setupSelectors(array_keys($this->selectors));
        
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
     * @dataProvider getGetSelectorsTestData
     * 
     * @param SelectionCollection $selectionCollection1
     * @param SelectionCollection $selectionCollection2
     */
    public function testGetSelectors(
        SelectionCollection $selectionCollection1,
        SelectionCollection $selectionCollection2
    ) {
        $this->selectors = array(
            'ServiceForum' => $this->getSelectorThatHasSelections($selectionCollection1),
            'ServicePage' => $this->getSelectorThatHasSelections($selectionCollection2)
        );
        
        $selectorManager = $this->getSelectorManager($this->getSelectorFactory());
        $selectorManager->setupSelectors(array_keys($this->selectors));
        
        $this->assertEquals(
            [
                'ServiceForum' => $selectionCollection1,
                'ServicePage' => $selectionCollection2
            ],
            $selectorManager->getCollections()
        );
    }
    
    /**
     * @dataProvider  getAreAllParametersSetTestData
     * 
     * @param SelectorInterface $selector1
     * @param SelectorInterface $selector2
     * @param bool              $expected
     */
    public function testAreAllParametersSet(
        $selector1,
        $selector2,
        $expected
    ) {
        $this->selectors = [
            'ServiceForum' => $selector1,
            'ServicePage' => $selector2
        ];
        
        $selectorManager = $this->getSelectorManager($this->getSelectorFactory());
        $selectorManager->setupSelectors(array_keys($this->selectors));
        
        $this->assertEquals($expected, $selectorManager->areAllParametersSet());
    }
    
    public function testGetParameters()
    {
        $selector1 = $this->getSelectorMock();
        $selector2 = $this->getSelectorMock();
        $selector3 = $this->getSelectorMock();
        
        $selector1->expects($this->once())->method('getParameters')->willReturn([
            'param1' => 'decision1'
        ]);
        $selector2->expects($this->once())->method('getParameters')->willReturn([
            'paramA' => 'decisionA',
            'paramB' => 'decisionB'
        ]);
        $selector3->expects($this->once())->method('getParameters')->willReturn([]);
        
        $this->selectors = [
            'ServiceForum' => $selector1,
            'ServicePage' => $selector2,
            'ServiceUser' => $selector3
        ];
        
        $selectorManager = $this->getSelectorManager($this->getSelectorFactory());
        $selectorManager->setupSelectors(array_keys($this->selectors));
        
        $expectedParams = [
            'ServiceForum' => ['param1' => 'decision1'],
            'ServicePage' => [
                'paramA' => 'decisionA',
                'paramB' => 'decisionB'
            ],
            'ServiceUser' => []
        ];
        
        $this->assertEquals($expectedParams, $selectorManager->getParameters());
    }
    
    public function getAreAllParametersSetTestData()
    {
        $selectorNotAllSet1 = $this->getSelectorMock();
        $selectorNotAllSet2 = $this->getSelectorMock();
        $selectorNotAllSet1->expects($this->any())->method('isParameterMissing')->willReturn(true);
        $selectorNotAllSet2->expects($this->any())->method('isParameterMissing')->willReturn(true);
        
        $selectorAllSet1 = $this->getSelectorMock();
        $selectorAllSet2 = $this->getSelectorMock();
        $selectorAllSet1->expects($this->any())->method('isParameterMissing')->willReturn(false);
        $selectorAllSet2->expects($this->any())->method('isParameterMissing')->willReturn(false);
        
        return [
            'both_missing_parameters' => [$selectorNotAllSet1, $selectorNotAllSet2, false],
            'first_missing_parameters' => [$selectorNotAllSet1, $selectorAllSet2, false],
            'second_missing_parameters' => [$selectorAllSet1, $selectorNotAllSet2, false],
            'both_not_missing_parameters' => [$selectorAllSet1, $selectorAllSet2, true]
        ];
    }
    
    public function getGetSelectorsTestData()
    {
        $selections1 = [
            new Selection('forum', array('1' => 'foo', '2' => 'bar'))
        ];
        $selections2 = [
            new Selection('firstParameter', array('1' => 'a', '2' => 'b')),
            new Selection('secondParameter', array('f' => 'foo', 'b' => 'bar'))
        ];
        
        return [
            [
                new SelectionCollection([], $selections1),
                new SelectionCollection([], $selections2)
            ]
        ];
    }
    
    /**
     * @param SelectorFactoryInterface $selectorFactory
     * 
     * @return SelectorManager
     */
    protected function getSelectorManager( SelectorFactoryInterface $selectorFactory)
    {
        return new SelectorManager(
            $selectorFactory,
            $this->getCollectionTransformer()
        );
    }
    
    /**
     * @return SelectionCollectionArrayTransformerInterface
     */
    protected function getCollectionTransformer()
    {
        $collectionTransformer = $this->createMock(SelectionCollectionArrayTransformerInterface::class);
        $collectionTransformer
            ->expects($this->any())
            ->method('getSelectionCollectionAsArray')
            ->willReturnCallback([$this, 'getSelectionCollectionAsArrayMock'])
        ;
        
        $collectionTransformer
            ->expects($this->any())
            ->method('getSelectionCollectionFromArray')
            ->willReturnCallback([$this, 'getSelectionCollectionFromArrayMock'])
        ;
            
        return $collectionTransformer;
    }
    
    /**
     * @return SelectorInterface
     */
    protected function getSelectorsThatUpdateParameters()
    {
        $selector = $this->getSelectorMock();
        $selector->expects($this->once())
            ->method('updateParameters');
        
        return $selector;
    }
    
    /**
     * @param SelectionCollection $selectionCollection
     * 
     * @return SelectorInterface
     */
    protected function getSelectorThatHasSelections(SelectionCollection $selectionCollection)
    {
        $selector = $this->getSelectorMock();
        $selector->expects($this->once())
            ->method('getCollection')
            ->willReturn($selectionCollection);
        
        return $selector;
    }
    
    /**
     * @return SelectorInterface
     */
    protected function getSelectorMock()
    {
        return $this->createMock(SelectorInterface::class);
    }
    
    protected function getSelectorFactory()
    {
        $factory = $this->getMockBuilder(SelectorFactoryInterface::class)->getMock();
        
        $factory->expects($this->any())
            ->method('getSelector')
            ->will($this->returnCallback(array($this, 'getSelectorCallback')));
        
        return $factory;
    }
    
    /**
     * @param string                            $entryId
     * @param string[]                          $additionalScopes
     * @param SelectionCollectionInterface|null $selectionCollection
     * 
     * @return SelectorInterface
     */
    public function getSelectorCallback(
        string $entryId,
        SelectionCollectionInterface $selectionCollection = null
    ) {
        $selector = $this->selectors[$entryId];
        if ($selectionCollection) {
            $selector->expects($this->any())->method('getCollection')->willReturn($selectionCollection);
        }
        
        return $selector;
    }
    
    /**
     * @param array $collectionData
     * 
     * @return SelectionCollection
     */
    public function getSelectionCollectionFromArrayMock(array $collectionData)
    {
        $selectionColletion = new SelectionCollection(
            isset($collectionData['decisions']) ? $collectionData['decisions'] : []
        );
        
        return $selectionColletion;
    }
    
    /**
     * @param SelectionCollectionInterface $selectionColelction
     * 
     * @return array
     */
    public function getSelectionCollectionAsArrayMock(SelectionCollectionInterface $selectionCollection)
    {
        
        return ['decisions' => $selectionCollection->getDecisions()];
    }
}