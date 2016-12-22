<?php

namespace Publisher\Entry\Service;

use Publisher\Entry\EntryInterface;

class ServicePageEntry implements EntryInterface
{
    
    public static function getPublisherScopes()
    {
        return array('pages');
    }
    
    public static function getId()
    {
        return 'ServicePage';
    }
    
    public static function getServiceId()
    {
        return 'Service';
    }
    
    public function getRequest()
    {
        
    }

    public function setBody(array $body)
    {
        
    }

}
