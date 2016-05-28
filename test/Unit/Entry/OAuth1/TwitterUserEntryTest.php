<?php

namespace Unit\Entry\OAuth1;

use Unit\Entry\EntryTest;

class TwitterUserEntryTest extends EntryTest
{
    
    protected function getEntryName()
    {
        return 'Publisher\\Entry\\OAuth1\TwitterUserEntry';
    }
    
    public function getValidBody()
    {
        return array(
            array(array('status' => 'foo'))
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
            array(array('status' => $this->getExceededMessage()))
        );
    }
}