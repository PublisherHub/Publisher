<?php

namespace Unit\Publisher\Supervision;

use Publisher\Supervision\PublisherSupervisor;
use Unit\Publisher\Helper\BaseEntryHelperTest;

class PublisherSupervisorTest extends BaseEntryHelperTest
{
    
    protected function getEntryHelper(array $config)
    {
        return new PublisherSupervisor($config);
    }
    
    // begin supervisor specific tests
    
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
            array('Service', array('ServiceUser', 'ServicePage'))
        );
    }
    
    public function testGetServices()
    {
        $services = array_keys($this->config['entries']);
        
        $this->assertEquals($services, $this->supervisor->getServices());
    }
    
    public function testGetEntrySubtypes()
    {
        $entrySubtypes = $this->config['entries'];
        
        $this->assertEquals($entrySubtypes, $this->supervisor->getEntrySubtypes());
    }
    
    public function testGetAllModes()
    {
        $allModes = $this->config['modes'];
        
        $this->assertEquals($allModes, $this->supervisor->getAllModes());
    }
    
    public function testGetModeEntity()
    {
        $modeClass = '\\Publisher\\Entry\\Service\\Mode\\Foo\\ServiceUserFoo';
        
        $this->assertEquals($modeClass, $this->supervisor->getModeClass('Foo', 'ServiceUser'));
    }
    
}
