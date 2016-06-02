<?php

namespace Unit\Monitoring;

use Publisher\Monitoring\Monitor;

class MonitorTest extends \PHPUnit_Framework_TestCase
{
    
    public function testConstructor()
    {
        $monitor = new Monitor('PublisherMonitor');
        $this->assertSame(array(), $monitor->getStatus());
        
    }
    
    public function testImportUpdatedStatus()
    {
        $updatedStatus = array(
            'entry1' => true,
            'entry2' => false
        );
        
        $_SESSION['PublisherMonitor'] = $updatedStatus;
        
        $monitor = new Monitor('PublisherMonitor');
        $this->assertEquals($updatedStatus, $monitor->getStatus());
    }
    
    public function testExecuted()
    {
        $updatedStatus = array(
            'entry1' => true,
            'entry2' => false,
            'entry3' => null
        );
        
        $_SESSION['PublisherMonitor'] = $updatedStatus;
        
        $monitor = new Monitor('PublisherMonitor');
        
        $this->assertTrue($monitor->executed('entry1'));
        
        $this->assertTrue($monitor->executed('entry2'));
        
        $this->assertFalse($monitor->executed('entry3'));
    }
    
    /**
     * @dataProvider getStatus
     */
    public function testToUpdateStatus(bool $result)
    {
        $monitor = new Monitor('PublisherMonitor');
        
        $monitor->monitor('entry');
        $expectedStatus = array('entry' => null);
        $this->assertSame($expectedStatus, $monitor->getStatus());
        
        $monitor->setStatus('entry', $result);
        $status = $monitor->getStatus();
        $this->assertSame($result, $status['entry']);
    }
    
    public function getStatus()
    {
        return array(
            array(true),
            array(false)
        );
    }
    
    /**
     * @expectedException \Publisher\Monitoring\Exception\UnregisteredEntryException
     */
    public function testFailToCheckIfUnregisteredEntryWasExecuted()
    {
        $monitor = new Monitor();
        $wasExecuted = $monitor->executed('entry');
    }
    
    /**
     * @expectedException \Publisher\Monitoring\Exception\UnregisteredEntryException
     */
    public function testFailToSetStatusForUnregisteredEntry()
    {
        $monitor = new Monitor();
        $monitor->setStatus('entry', true);
    }
    
}