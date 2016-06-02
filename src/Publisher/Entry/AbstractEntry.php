<?php

namespace Publisher\Entry;

use Publisher\Entry\EntryInterface;
use Publisher\Entry\Interfaces\RecommendationInterface;
use Publisher\Monitoring\MonitoredInterface;

abstract class AbstractEntry implements EntryInterface, RecommendationInterface, MonitoredInterface
{
    
    const MAX_LENGTH_OF_MESSAGE = 0;
    
    protected $path;
    protected $method;
    protected $contentType;
    protected $body;
    
    public function __construct(array $parameters = array())
    {
        $this->body = array();
    }
    
    protected abstract function validateBody(array $body);
    
    // EntryInterface
    public function getPath()
    {
        return $this->path;
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function getContentType()
    {
        return $this->contentType;
    }
    
    public function getBody()
    {
        $this->validateBody($this->body);
        return $this->body;
    }
    
    public function setBody(array $body)
    {
        $this->body = $body;
    }
    
    public function getName()
    {
        $classname = get_class($this);
        return preg_replace('/^.*\\\\/', '', $classname);
    }
}