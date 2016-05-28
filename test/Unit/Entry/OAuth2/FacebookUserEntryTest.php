<?php

namespace Unit\Entry\OAuth2;

use Unit\Entry\EntryTest;

class FacebookUserEntryTest extends EntryTest
{
    
    protected function getEntryName()
    {
        return 'Publisher\\Entry\\OAuth2\FacebookUserEntry';
    }
    
    public function getValidBody()
    {
        return array(
            array(array('message' => 'foo')),
            array(array('link' => 'foo')),
            array(array('place' => 'foo')),
            array(array('message' => 'foo', 'link' => 'foo', 'place' => 'foo', 'tags' => '123'))
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