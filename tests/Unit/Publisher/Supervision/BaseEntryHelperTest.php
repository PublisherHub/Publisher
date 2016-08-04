<?php

namespace Unit\Publisher\Supervision;

use Unit\Publisher\Helper\BaseEntryHelperTest as BaseTest;
use Publisher\Supervision\PublisherSupervisor;

class BaseEntryHelperTest extends BaseTest
{
    
    /**
     * @dataProvider getEntryIdWithService
     * 
     * @param string $entryId
     * @param string $service
     */
    public function testGetServiceId(string $entryId, string $service)
    {
        $this->assertEquals(
                $service,
                $this->entryHelper->getServiceId($entryId)
        );
    }
    
    public function getEntryIdWithService()
    {
        return array(
            array('FacebookPage', 'Facebook'),
            array('FacebookUser', 'Facebook'),
            array('TwitterUser', 'Twitter'),
            array('XingForum', 'Xing'),
            array('XingUser', 'Xing'),
        );
    }
    
    /**
     * @dataProvider getUnknownEntryIds
     * 
     * @expectedException \Publisher\Entry\Exception\EntryNotFoundException
     */
    public function testGetNoServiceId(string $entryId)
    {
        $service = $this->entryHelper->getServiceId($entryId);
    }
    
    public function getUnknownEntryIds()
    {
        return array(
            array(''),
            array('User'),
            array('Page'),
            array('Group'),
            array('Forum'),
            array('ServiceUser'),
            array('ServicePage'),
            array('ServiceGroup'),
            array('ServiceForum'),
            
        );
    }
    
    protected function getEntryHelper(array $config)
    {
        return new PublisherSupervisor($config);
    }
    
}