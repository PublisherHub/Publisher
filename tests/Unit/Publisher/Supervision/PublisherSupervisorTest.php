<?php

namespace Unit\Publisher\Supervision;

use Publisher\Supervision\PublisherSupervisor;

class PublisherSupervisorTest extends \PHPUnit_Framework_TestCase
{
    
    protected $config;
    protected $supervisor;
    
    public function __construct()
    {
        $this->config = array(
            'entryIds' => array(
                'Facebook' => array('User', 'Page'),
                'Twitter' => array('User'),
                'Xing' => array('User', 'Forum')
            ),
            'modes' => array(
                'Recommendation'
            )
        );
    }
    
    public function setUp()
    {
        $this->supervisor = new PublisherSupervisor($this->config);
    }
    
    public function testGetServices()
    {
        $services = array_keys($this->config['entryIds']);
        
        $this->assertEquals($services, $this->supervisor->getServices());
    }
    
    public function testGetEntrySubTypes()
    {
        $entrySubTypes = $this->config['entryIds'];
        
        $this->assertEquals($entrySubTypes, $this->supervisor->getEntrySubTypes());
    }
    
    public function testGetAllModes()
    {
        $allModes = $this->config['modes'];
        
        $this->assertEquals($allModes, $this->supervisor->getAllModes());
    }
    
}