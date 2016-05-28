<?php

namespace Unit\Monitoring;

use Publisher\Monitoring\Monitor;

class MonitorTest extends \PHPUnit_Framework_TestCase
{
    
    public function testConstructor()
    {
        $monitor = new Monitor();
        $this->assertEquals(array(), $monitor->getResults());
    }
    
    public function testImportUpdatedResults()
    {
        $updatedResults = array(
            'entry1' => true,
            'entry2' => false
        );
        
        $monitor = new Monitor($updatedResults);
        $this->assertEquals($updatedResults, $monitor->getResults());
    }
    
    public function testExecuted()
    {
        $updatedResults = array(
            'entry1' => true,
            'entry2' => false,
            'entry3' => null
        );
        
        $monitor = new Monitor($updatedResults);
        
        $this->assertTrue($monitor->executed('entry1'));
        
        $this->assertTrue($monitor->executed('entry2'));
        
        $this->assertFalse($monitor->executed('entry3'));
    }
    
    /**
     * @dataProvider getResult
     */
    public function testToUpdateResults(bool $result)
    {
        $monitor = new Monitor();
        
        $monitor->monitor('entry');
        $expectedResults = array('entry' => null);
        $this->assertSame($expectedResults, $monitor->getResults());
        
        $monitor->setResult('entry', $result);
        $results = $monitor->getResults();
        $this->assertSame($result, $results['entry']);
    }
    
    public function getResult()
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
    public function testFailToSetResultForUnregisteredEntry()
    {
        $monitor = new Monitor();
        $monitor->setResult('entry', true);
    }
    
}