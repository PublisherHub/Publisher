<?php

namespace Unit\Publisher\Helper;

abstract class BaseEntryHelperTest extends \PHPUnit_Framework_TestCase
{
    
    protected $config;
    protected $entryHelper;
    
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        $this->config = array(
            'entries' => array(
                'Service' => array('User', 'Page')
            ),
            'modes' => array(
                'Foo'
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
            array('ServicePage', 'Service'),
            array('ServiceUser', 'Service')
        );
    }
    
    public function testGetEntryClass()
    {
        $entryClass = '\\Publisher\\Entry\\Service\\ServiceUserEntry';
        
        $this->assertSame(
                $entryClass,
                $this->entryHelper->getEntryClass('ServiceUser')
        );
    }
    
    public function testGetSelectorClass()
    {
        $selectorClass = '\\Publisher\\Entry\\Service\\Selector\\ServicePageSelector';
        
        $this->assertSame(
                $selectorClass,
                $this->entryHelper->getSelectorClass('ServicePage')
        );
    }
    
    /**
     * @expectedException Publisher\Selector\Exception\SelectorNotFoundException
     */
    public function testSelectorNotFound()
    {
        $selectorClass = $this->entryHelper->getSelectorClass('ServiceUser');
    }
    
    public function testGetModeClass()
    {
        $modeClass = '\\Publisher\\Mode\\Foo\\AbstractFoo';
        
        $this->assertSame($modeClass, $this->entryHelper->getModeClass('Foo'));
    }
    
    abstract protected function getEntryHelper(array $config);
    
}