<?php

namespace Unit\Publisher\Manager;

use PHPUnit\Framework\TestCase;
use Publisher\Manager\Publisher;
use Publisher\Supervision\PublisherSupervisor;
use Publisher\Helper\EntryHelper;
use Publisher\Entry\Factory\EntryFactoryInterface;
use Publisher\Requestor\RequestorFactoryInterface;
use Publisher\Monitoring\Monitor;

abstract class AbstractPublisherTest extends TestCase
{
    
    protected function getPublisher(array $entryData)
    {
        $publisher = new Publisher(
            $this->getEntryHelper(),
            $this->getEntryFactoryMock(),
            $this->getRequestorFactoryMock(),
            $this->getMonitor()
        );
        $publisher->setupEntries($entryData);
        
        return $publisher;
    }
    
    /**
     * @dataProvider getTestContent
     */
    public function testEntryDataImport(array $content)
    {    
        $entryData = array();
        $entryData[] = array(
            'entry' => 'ServiceUser',
            'content' => $content,
            'mode' => $this->getModeId()
        );
        
        $publisher = $this->getPublisher($entryData);
        
        $expectedStatus = array('ServiceUser' => null);
        $this->assertEquals($expectedStatus, $publisher->getStatus());
        $this->assertFalse($publisher->hasFinished());
        
        $publisher->clearStatus();
        $this->assertEquals(array(), $publisher->getStatus());
    }
    
    /**
     * As a data provider it returns the content the mode would expect.
     * 
     * @return array
     */
    public abstract function getTestContent();
    
    protected function getEntryFactoryMock()
    {
        return $this->getMockBuilder(EntryFactoryInterface::class)->getMock();
    }
    
    protected function getRequestorFactoryMock()
    {
        return $this->getMockBuilder(RequestorFactoryInterface::class)->getMock();
    }
    
    protected function getEntryHelper()
    {
        $config = array(
            'entries' => array(
                'Service' => array('User')
            ),
            'modes' => array('Foo')
        );
        
        $supervisor = new PublisherSupervisor($config);
        return new EntryHelper($supervisor);
    }
    
    protected function getMonitor()
    {
        return new Monitor();
    }
    
    /**
     * Returns the mode Id that is used for this test class.
     * 
     * @return string
     */
    protected abstract function getModeId();
    
}