<?php

namespace Unit\Publisher\Helper;

use Publisher\Helper\EntryHelper;
use Publisher\Supervision\PublisherSupervisor;

abstract class BaseEntryHelperTest extends \PHPUnit_Framework_TestCase
{
    
    protected $config;
    protected $entryHelper;
    
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
        
        $this->entryHelper = $this->getEntryHelper($this->config);
        
        parent::__construct($name, $data, $dataName);
    }
    
    /**
     * @dataProvider getServices
     */
    public function testGetServiceId(string $entryId, string $service)
    {
        $this->assertSame($service, $this->entryHelper->getServiceId($entryId));
    }
    
    public function getServices()
    {
        return array(
            array('FacebookPage', 'Facebook'),
            array('TwitterUser', 'Twitter'),
            array('XingForum', 'Xing')
        );
    }
    
    public function testGetEntryClass()
    {
        $this->config['entryIds']['Mock'] = array('User');
        $this->entryHelper = $this->getEntryHelper($this->config);
        $entryClass = '\\Publisher\\Entry\\Mock\\MockUserEntry';
        
        $this->assertSame(
                $entryClass,
                $this->entryHelper->getEntryClass('MockUser')
        );
    }
    
    public function testGetSelectorClass()
    {
        $this->config['entryIds']['Mock'] = array('Page');
        $this->entryHelper = $this->getEntryHelper($this->config);
        $selectorClass = '\\Publisher\\Entry\\Mock\\Selector\\MockPageSelector';
        
        $this->assertSame(
                $selectorClass,
                $this->entryHelper->getSelectorClass('MockPage')
        );
    }
    
    /**
     * @expectedException Publisher\Selector\Exception\SelectorNotFoundException
     */
    public function testSelectorNotFound()
    {
        $this->config['entryIds']['Mock'] = array('User');
        $this->entryHelper = $this->getEntryHelper($this->config);
        
        $selectorClass = $this->entryHelper->getSelectorClass('MockUser');
    }
    
    public function testGetModeClass()
    {
        $this->config['modes'][] = array('Mock');
        $this->entryHelper = $this->getEntryHelper($this->config);
        $modeClass = '\\Publisher\\Mode\\Mock\\MockMode';
        
        $this->assertSame($modeClass, $this->entryHelper->getModeClass('Mock'));
    }
    
    protected function getEntryHelper(array $config)
    {
        $supervisor = new PublisherSupervisor($config);
        return new EntryHelper($supervisor);
    }
    
}