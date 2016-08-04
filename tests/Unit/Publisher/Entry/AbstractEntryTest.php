<?php

namespace Unit\Publisher\Entry;

use Unit\Publisher\Entry\EntryTest;

class AbstractEntryTest extends EntryTest
{
    
    public function testGetId()
    {
        $entryClass = $this->getEntryClass();
        
        $this->assertEquals('MockUser', $entryClass::getId());
    }
    
    public function testGetServiceId()
    {
        $entryClass = $this->getEntryClass();
        
        $this->assertEquals('Mock', $entryClass::getServiceId());
    }
    
    protected function getEntryClass()
    {
        return 'Publisher\\Entry\\Mock\\MockUserEntry';
    }

    public function getBodyWithExceededMessage()
    {
        return array(
            array(array('message' => $this->getExceededMessage()))
        );
    }

    public function getInvalidBody()
    {
        return array(
            array(array()),
            array(array('message' => null))
        );
    }

    public function getValidBody()
    {
        return array(
            array(array('message' => 'Hello World!'))
        );
    }

}