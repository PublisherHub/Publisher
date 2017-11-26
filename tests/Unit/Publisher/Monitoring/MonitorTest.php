<?php

namespace Unit\Publisher\Monitoring;

use PHPUnit\Framework\TestCase;
use Publisher\Monitoring\Monitor;

class MonitorTest extends TestCase
{
    
    public function testDefaults()
    {
        $monitor = new Monitor();
        $this->assertEquals([], $monitor->getStatus());
        $this->assertTrue($monitor->finished());
    }
    
    public function testInitialStatus()
    {
        $initialStatus = [
            'ProviderPage' => null,
            'ServiceUser' => true
        ];
        
        $monitor = new Monitor($initialStatus);
        $this->assertEquals($initialStatus, $monitor->getStatus());
    }
    
    /**
     * @expectedException \Publisher\Monitoring\Exception\UnregisteredEntryException
     */
    public function testGetNoStatus()
    {
        $monitor = new Monitor();
        
        $monitor->setStatus('NotRegistered', true);
    }
    
    public function testSetStatus()
    {
        $monitor = new Monitor();
        
        $monitor->monitor('ProviderPage');
        $monitor->monitor('ServiceUser');
         $this->assertFalse($monitor->finished());
        
        $status = $monitor->getStatus();
        $this->assertEquals([
            'ProviderPage' => null,
            'ServiceUser' => null
        ], $status);
        $this->assertFalse($monitor->executed('ProviderPage'));
        $this->assertFalse($monitor->executed('ServiceUser'));
        
        
        $monitor->setStatus('ProviderPage', true);
        $this->assertFalse($monitor->finished());
        $monitor->setStatus('ServiceUser', false);
        $this->assertTrue($monitor->finished());
        
        $status = $monitor->getStatus();
        $this->assertEquals([
            'ProviderPage' => true,
            'ServiceUser' => false
        ], $status);
        $this->assertTrue($monitor->executed('ProviderPage'));
        $this->assertTrue($monitor->executed('ServiceUser'));
        
        $monitor->clearStatus();
        $this->assertEquals([], $monitor->getStatus());
        $this->assertTrue($monitor->finished());
    }
}
