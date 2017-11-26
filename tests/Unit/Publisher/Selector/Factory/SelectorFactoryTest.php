<?php

namespace Unit\Publisher\Selector\Factory;

use PHPUnit\Framework\TestCase;
use Publisher\Helper\EntryHelper;
use Publisher\Requestor\RequestorFactoryInterface;
use Publisher\Requestor\RequestorInterface;
use Publisher\Selector\Factory\SelectorFactory;
use Publisher\Selector\Selection\SelectionCollection;
use Publisher\Selector\Selector;
use Publisher\Supervision\PublisherSupervisor;
use Publisher\Selector\NullSelector;

class SelectorFactoryTest extends TestCase // @todo REFAC
{
    
    /**
     * @var string[] 
     */
    protected $testScopes = [];
    
    /**
     * @var string[]
     */
    protected $currentScopes;
    
    
    public function testGetSelector()
    {
        $entryId = 'ServicePage';
        $publisherConfig = ['entries' => ['Service' => ['Page']]];
        $supervisor = new PublisherSupervisor($publisherConfig);
        $entryHelper = new EntryHelper($supervisor);
        
        $this->testScopes = array_merge(['status'], $entryHelper->getPublisherScopes($entryId));
        $requestorFactory = $this->createMock(RequestorFactoryInterface::class);
        $requestorFactory
            ->expects($this->once())
            ->method('create')
            ->with('Service', $this->testScopes)
            ->willReturnCallback([$this, 'getRequestorMock']);
        
        $selectorFactory = new SelectorFactory(
            $entryHelper,
            $requestorFactory,
            ['ServicePage' => ['status']]
        );
        
        $selector = null;
        $decisions = ['foo' => 'bar'];
        $selectionCollection = new SelectionCollection($decisions);
        $selector = $selectorFactory->getSelector($entryId, $selectionCollection);
        $this->assertInstanceOf(Selector::class, $selector);
        $this->assertSame($selectionCollection, $selector->getCollection());
        
        // make sure the created Requestor was initialized with the same scopes
        $selector->executeCurrentStep();
    }
    
    public function testGetSelectorWithDefaultSelectionCollection()
    {
        $entryId = 'ServicePage';
        $publisherConfig = ['entries' => ['Service' => ['Page']]];
        $supervisor = new PublisherSupervisor($publisherConfig);
        $entryHelper = new EntryHelper($supervisor);
        
        $requestorFactory = $this->createMock(RequestorFactoryInterface::class);
        $requestorFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->createMock(RequestorInterface::class));
        
        $selectorFactory = new SelectorFactory(
            $entryHelper,
            $requestorFactory
        );
        
        $selector = $selectorFactory->getSelector($entryId);
        $this->assertInstanceOf(Selector::class, $selector);
        $this->assertInstanceOf(SelectionCollection::class, $selector->getCollection());
    }
    
    /**
     * Test to create a default Selector,
     * when no Selector is defined for the Entry.
     */
    public function testGetDefaultSelector()
    {
        $entryId = 'ServiceUser';
        $publisherConfig = ['entries' => ['Service' => ['User']]];
        $supervisor = new PublisherSupervisor($publisherConfig);
        $entryHelper = new EntryHelper($supervisor);
        $requestorFactory = $this->createMock(RequestorFactoryInterface::class);
        
        $selectorFactory = new SelectorFactory($entryHelper, $requestorFactory);
        
        $selector = $selectorFactory->getSelector($entryId);
        $this->assertInstanceOf(NullSelector::class, $selector);
        $this->assertInstanceof(SelectionCollection::class, $selector->getCollection());
    }
    
    public function getRequestorMock(string $serviceId, array $scopes = [])
    {
        $requestor = $this->createMock(RequestorInterface::class);
        $requestor->expects($this->once())->method('doRequest')->willReturn('foo');
        
        return $requestor;
    }
    
}
