<?php

namespace Unit\Publisher\Manager;

use Publisher\Requestor\RequestorFactoryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Publisher\Monitoring\Monitor;
use Publisher\Supervision\PublisherSupervisor;

abstract class AbstractPublishingManagerTest extends \PHPUnit_Framework_TestCase
{
    
    protected abstract function getManager(
            array $entryData,
            RequestorFactoryInterface $requestorFactory,
            Monitor $monitor
    );
    
    public function testEntryDataImport()
    {
        $content = $this->getTestContent();
        
        $entries = array();
        $entries[] = array(
            'entry' => 'MockUser',
            'content' => $content
        );
        
        $requestorFactory = $this->getMock('\\Publisher\\Requestor\\RequestorFactoryInterface');
        $monitor = $this->getMonitor();
        
        $manager = $this->getManager($entries, $requestorFactory, $monitor);
        
        $expectedStatus = array('MockUser' => null);
        $this->assertSame($expectedStatus, $manager->getStatus());
    }
    
    protected function getEntryHelper()
    {
        $config = array(
            'entryIds' => array(
                'Mock' => array('User')
            ),
            'modes' => array('Mock')
        );
        
        return new PublisherSupervisor($config);
    }
    
    protected function getMonitor()
    {
        $session = new Session(new MockArraySessionStorage());
        return Monitor::getInstance($session);
    }
    
}