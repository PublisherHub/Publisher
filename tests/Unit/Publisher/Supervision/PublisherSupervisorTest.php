<?php

namespace Unit\Publisher\Supervision;

use Publisher\Supervision\PublisherSupervisor;

class PublisherSupervisorTest extends \PHPUnit_Framework_TestCase
{
    
    protected $config;
    protected $supervisor;
    
    public function __construct($name = NULL, array $data = array(), $dataName = '')
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
        
        parent::__construct($name, $data, $dataName);
    }
    
    public function setUp()
    {
        $this->supervisor = new PublisherSupervisor($this->config);
    }
    
    /**
     * @dataProvider getServiceWithEntryIds
     * 
     * @param string $service
     * @param array $entryIds
     */
    public function testGetEntryIds(string $service, array $entryIds)
    {
        $this->assertEquals($entryIds, $this->supervisor->getEntryIds($service));
    }
    
    public function getServiceWithEntryIds()
    {
        return array(
            array('Facebook', array('FacebookUser', 'FacebookPage')),
            array('Service', array())
        );
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