<?php

namespace Unit\Publisher\Selector\Factory;

use Publisher\Helper\EntryHelper;
use Publisher\Requestor\RequestorFactoryInterface;
use Publisher\Requestor\RequestorInterface;
use Publisher\Selector\Factory\SelectorFactory;
use Publisher\Selector\Selection\SelectionCollection;
use Publisher\Selector\Selector;
use Publisher\Supervision\PublisherSupervisor;
use Publisher\Selector\NullSelector;

class SelectorFactoryTest extends \PHPUnit_Framework_TestCase
{
    
    public function testCreate()
    {
        $entryId = 'ServicePage';
        $publisherConfig = ['entries' => ['Service' => ['Page']]];
        $additionalScopes = ['status'];
        $supervisor = new PublisherSupervisor($publisherConfig);
        $entryHelper = new EntryHelper($supervisor);
        $requestorFactory = $this->createMock(RequestorFactoryInterface::class);
        $mergedScopes = array_merge($additionalScopes, $entryHelper->getPublisherScopes($entryId));
        $requestorFactory->expects($this->once())
            ->method('create')
            ->with($entryHelper->getServiceId($entryId), $mergedScopes)
            ->willReturn($this->createMock(RequestorInterface::class))
        ;
        $selectorFactory = new SelectorFactory($entryHelper, $requestorFactory);
        
        $selector  = $selectorFactory->create($entryId, $additionalScopes);
        $this->assertInstanceOf(Selector::class, $selector);
        $this->assertInstanceof(SelectionCollection::class, $selector->getCollection());
    }
    
    /**
     * Test to create a default Selector.
     */
    public function testCreateDefault()
    {
        $entryId = 'ServiceUser';
        $publisherConfig = ['entries' => ['Service' => ['User']]];
        $additionalScopes = ['status'];
        $supervisor = new PublisherSupervisor($publisherConfig);
        $entryHelper = new EntryHelper($supervisor);
        $requestorFactory = $this->createMock(RequestorFactoryInterface::class);
        
        $selectorFactory = new SelectorFactory($entryHelper, $requestorFactory);
        
        $selector  = $selectorFactory->create($entryId, $additionalScopes);
        $this->assertInstanceOf(NullSelector::class, $selector);
        $this->assertInstanceof(SelectionCollection::class, $selector->getCollection());
    }
    
}
