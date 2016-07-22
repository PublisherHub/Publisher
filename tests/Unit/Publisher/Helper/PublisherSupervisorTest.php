<?php

namespace Unit\Publisher\Helper;

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
        
        $this->supervisor = new PublisherSupervisor($this->config);
        
        parent::__construct($name, $data, $dataName);
    }
    
    /**
     * @dataProvider getServices
     */
    public function testGetServices(string $entryId, string $service)
    {
        $this->assertSame($service, $this->supervisor->getServiceId($entryId));
    }
    
    public function getServices()
    {
        return array(
            array('FacebookPage', 'Facebook'),
            array('TwitterUser', 'Twitter'),
            array('XingForum', 'Xing')
        );
    }
    
    /**
     * @dataProvider getConfiguredEntryIds
     */
    public function testCheckIsEntryId(string $entryId)
    {
        $exception = null;
        try {
            $this->supervisor->checkIsEntryId($entryId);
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
    
    public function testGetPublisherScopes()
    {
        $this->config['entryIds']['Mock'] = array('User');
        $this->supervisor = new PublisherSupervisor($this->config);
        
        $this->assertSame(array(), $this->supervisor->getPublisherScopes('MockUser'));   
    }
    
    public function testGetEntryClass()
    {
        $this->config['entryIds']['Mock'] = array('User');
        $this->supervisor = new PublisherSupervisor($this->config);
        $entryClass = '\\Publisher\\Entry\\Mock\\MockUserEntry';
        
        $this->assertSame($entryClass, $this->supervisor->getEntryClass('MockUser'));
    }
    
    public function testGetSelectorClass()
    {
        $this->config['entryIds']['Mock'] = array('Page');
        $this->supervisor = new PublisherSupervisor($this->config);
        $selectorClass = '\\Publisher\\Entry\\Mock\\Selector\\MockPageSelector';
        
        $this->assertSame($selectorClass, $this->supervisor->getSelectorClass('MockPage'));
    }
    
    /**
     * @expectedException Publisher\Selector\Exception\SelectorNotFoundException
     */
    public function testSelectorNotFound()
    {
        $this->config['entryIds']['Mock'] = array('User');
        $this->supervisor = new PublisherSupervisor($this->config);
        // Not every Entry has it's own Selector
        $selectorClass = $this->supervisor->getSelectorClass('MockUser');
    }
}