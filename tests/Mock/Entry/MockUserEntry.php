<?php

namespace Publisher\Entry\Mock;

use Publisher\Entry\EntryInterface;

class MockUserEntry implements EntryInterface
{
    
    public static function getPublisherScopes()
    {
        return array();
    }
    
    public static function getId()
    {
        return 'MockUser';
    }
    
    public static function getServiceId()
    {
        return 'Mock';
    }

    public function getRequest()
    {
        
    }

    public function setBody(array $body)
    {
        
    }

}
