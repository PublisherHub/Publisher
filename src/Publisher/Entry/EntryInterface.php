<?php

namespace Publisher\Entry;

interface EntryInterface
{
    
    /**
     * @return String classname of Entry
     */
    public static function getId();
    
    /**
     * @return String corresponding service id
     */
    public static function getServiceId();
    
    /**
     * @return array
     */
    public static function getPublisherScopes();
    
    public function setBody(array $body);
    
    /**
     * @return \Publisher\Requestor\Request
     */
    public function getRequest();
    
}
