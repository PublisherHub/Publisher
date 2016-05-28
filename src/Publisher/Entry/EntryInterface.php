<?php

namespace Publisher\Entry;

interface EntryInterface
{
    
    public function getPath();
    
    public function getMethod();
    
    public function getContentType();
    
    public function getBody();
    
    public function setBody(array $body);
    
    /**
     * @return String classname of Entry
     */
    public function getName();
    
}
