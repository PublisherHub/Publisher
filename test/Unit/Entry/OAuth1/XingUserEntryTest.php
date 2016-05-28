<?php

namespace Unit\Entry\OAuth1;

use Unit\Entry\EntryTest;

class XingUserEntryTest extends EntryTest
{
    
    protected function getEntryName()
    {
        return 'Publisher\\Entry\\OAuth1\XingUserEntry';
    }
    
    public function getValidBody()
    {
        return array(
            array(array('message' => 'foo'))
        );
    }
    
    public function getInvalidBody()
    {
        return array(
            array(array()),
            array(array('notRequired' => 'foo'))
        );
    }
    
    public function getBodyWithExceededMessage()
    {
        return array(
            array(array('message' => $this->getExceededMessage()))
        );
    }
    
}