<?php

namespace Unit;

use Publisher\Interfaces\PublisherFactoryInterface;
use Publisher\Monitoring\Monitor;

abstract class AbstractPublishingManagerTest extends \PHPUnit_Framework_TestCase
{
    
    protected abstract function getManager(
            array $entryData,
            PublisherFactoryInterface $publisherFactory,
            Monitor $monitor
    );
    
    public function testEntryDataImport()
    {
        $entryData = array(
            'TwitterUserEntry' => $this->getTestContent()
        );
        $publishingFactory = $this->getMock('\\Publisher\\Interfaces\\PublisherFactoryInterface');
        $monitor = new Monitor();
        
        $manager = $this->getManager($entryData, $publishingFactory, $monitor);
        
        $expectedStatus = array('TwitterUserEntry' => null);
        $this->assertSame($expectedStatus, $manager->getStatus());
    }
    
}