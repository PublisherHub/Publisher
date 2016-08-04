<?php

namespace Unit\Publisher\Helper;

use Unit\Publisher\Helper\BaseEntryHelperTest;
use Publisher\Helper\EntryHelper;
use Publisher\Supervision\PublisherSupervisor;

class EntryHelperTest extends BaseEntryHelperTest
{
    
    /**
     * @dataProvider getConfiguredEntryIds
     */
    public function testCheckIsEntryId(string $entryId)
    {
        $exception = null;
        try {
            $this->entryHelper->checkIsEntryId($entryId);
        } catch (Exception $exception) {}
        
        $this->assertNull($exception);
    }
    
    public function getConfiguredEntryIds()
    {
        return array(
            array('FacebookPage'),
            array('TwitterUser'),
            array('XingForum')
        );
    }
    
    /**
     * @expectedException \Publisher\Entry\Exception\EntryNotFoundException
     */
    public function testEntryNotFound()
    {
        $id = 'ServiceUser';
        $this->entryHelper->checkIsEntryId($id);
    }
    
    public function testGetPublisherScopes()
    {
        $this->config['entryIds']['Mock'] = array('User');
        $this->entryHelper = $this->getEntryHelper($this->config);
        
        $this->assertSame(array(), $this->entryHelper->getPublisherScopes('MockUser'));   
    }
    
    protected function getEntryHelper(array $config)
    {
        $supervisor = new PublisherSupervisor($config);
        return new EntryHelper($supervisor);
    }
    
}