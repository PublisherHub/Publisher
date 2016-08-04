<?php

namespace Unit\Publisher\Mode;

use Publisher\Entry\EntryInterface;

abstract class AbstractModeTest extends \PHPUnit_Framework_TestCase
{
    
    public function testImplementsInterface()
    {
        $entry = $this->getTestEntry();
        
        $exception = null;
        try {
            $this->checkImplementsMode($entry);
            
        } catch (Exception $exception) {}
        
        $this->assertNull($exception);
    }
    
    /**
     * @expectedException \Publisher\Mode\Exception\InterfaceRequiredException
     */
    public function testDoesNotImplementsInterface()
    {
        $entry = $this->getMockForAbstractClass('Publisher\\Entry\\AbstractEntry');
        
        $this->checkImplementsMode($entry);
    }
    
    /**
     * @dataProvider getTestContent
     */
    public function testCheckValidContent(array $content)
    {
        $exception = null;
        try {
            $this->checkContent($content);
            
        } catch (Exception $exception) {}
        
        $this->assertNull($exception);
    }
    
    /**
     * Returns an object that implements EntryInterface and
     * the interface of the mode that is tested.
     * 
     * @return EntryInterface
     */
    protected abstract function getTestEntry();
    
    /**
     * Execute the checkImplementsMode method of the mode that is tested.
     * 
     * @return void
     */
    protected abstract function checkImplementsMode(EntryInterface $entry);
    
    
    /**
     * As a data provider it returns the content the mode would expect.
     * 
     * @return array
     */
    public abstract function getTestContent();
    
    /**
     * Execute the checkImplementsMode method of the mode that is tested.
     * 
     * @return void
     */
    protected abstract function checkContent(array $content);
    
}