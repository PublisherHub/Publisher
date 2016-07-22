<?php

namespace Publisher\Entry\Mock;

use Publisher\Entry\EntryInterface;

class MockPageEntry implements EntryInterface
{
    
    public static function getPublisherScopes()
    {
        return array('pages');
    }
    
    public static function getId()
    {
        return 'MockPage';
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
