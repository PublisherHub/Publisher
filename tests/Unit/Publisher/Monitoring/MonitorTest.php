<?php

namespace Unit\Publisher\Monitoring;

use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Publisher\Monitoring\Monitor;


class MonitorTest extends \PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {
        $this->session = new Session(new MockArraySessionStorage());
        $this->monitor = Monitor::getInstance($this->session);
    }
    
    public function tearDown()
    {
        $this->monitor->clearStatus();
    }
    
    public function testSingleton()
    {
        $this->assertSame($this->monitor, Monitor::getInstance($this->session));
    }
    
    public function testConstructor()
    {
        $this->assertSame(array(), $this->monitor->getStatus());
    }
    
    public function testMonitored()
    {
        $this->monitor->monitor('entryId');
        $this->assertFalse($this->monitor->executed('entryId'));
    }
    
    /**
     * @dataProvider getExecutedEntries
     */
    public function testExecuted($entryId, $succeeded)
    {
        $this->monitor->monitor($entryId);
        $this->monitor->setStatus($entryId, $succeeded);
        $this->assertTrue($this->monitor->executed($entryId));
    }
    
    public function getExecutedEntries()
    {
        return array(
            array('entry1', true),
            array('entry2', false)
        );
    }
    
    /**
     * @expectedException \Publisher\Monitoring\Exception\UnregisteredEntryException
     */
    public function testFailToCheckIfUnregisteredEntryWasExecuted()
    {
        $wasExecuted = $this->monitor->executed('entry');
    }
    
    /**
     * @expectedException \Publisher\Monitoring\Exception\UnregisteredEntryException
     */
    public function testFailToSetStatusForUnregisteredEntry()
    {
        $this->monitor->setStatus('entry', true);
    }
    
    public function testSetAndClearStatus()
    {
        $this->monitor->monitor('entry1');
        $this->monitor->monitor('entry2');
        $this->monitor->monitor('entry3');
        
        $this->monitor->setStatus('entry1', true);
        $this->monitor->setStatus('entry2', false);
        
        $expectedStatus = array(
            'entry1' => true,
            'entry2' => false,
            'entry3' => null
        );
        $this->assertSame($expectedStatus, $this->monitor->getStatus());
        
        $this->monitor->clearStatus();
        $this->assertSame(array(), $this->monitor->getStatus());
    }
    
}