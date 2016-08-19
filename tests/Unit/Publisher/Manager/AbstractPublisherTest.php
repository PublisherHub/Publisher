<?php

namespace Unit\Publisher\Manager;

use Publisher\Manager\Publisher;
use Publisher\Supervision\PublisherSupervisor;
use Publisher\Helper\EntryHelper;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Publisher\Monitoring\Monitor;

abstract class AbstractPublisherTest extends \PHPUnit_Framework_TestCase
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
            'entry' => 'MockUser',
            'content' => $content,
            'mode' => $this->getModeId()
        );
        
        $publisher = $this->getPublisher($entryData);
        
        $expectedStatus = array('MockUser' => null);
        $this->assertSame($expectedStatus, $publisher->getStatus());
        $this->assertSame(false, $publisher->hasFinished());
        
        $publisher->clearStatus();
        $this->assertSame(array(), $publisher->getStatus());
    }
    
    /**
     * As a data provider it returns the content the mode would expect.
     * 
     * @return array
     */
    public abstract function getTestContent();
    
    protected function getEntryFactoryMock()
    {
        return $this->getMock('\\Publisher\\Entry\\Factory\\EntryFactoryInterface');
    }
    
    protected function getRequestorFactoryMock()
    {
        return $this->getMock('\\Publisher\\Requestor\\RequestorFactoryInterface');
    }
    
    protected function getEntryHelper()
    {
        $config = array(
            'entries' => array(
                'Mock' => array('User')
            ),
            'modes' => array('Mock')
        );
        
        $supervisor = new PublisherSupervisor($config);
        return new EntryHelper($supervisor);
    }
    
    protected function getMonitor()
    {
        $session = new Session(new MockArraySessionStorage());
        return Monitor::getInstance($session);
    }
    
    /**
     * Returns the mode Id that is used for this test class.
     * 
     * @return string
     */
    protected abstract function getModeId();
    
}