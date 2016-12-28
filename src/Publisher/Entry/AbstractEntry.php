<?php

namespace Publisher\Entry;

use Publisher\Entry\EntryInterface;
use Publisher\Monitoring\MonitoredInterface;

use Publisher\Requestor\Request;

abstract class AbstractEntry implements EntryInterface, MonitoredInterface
{
    
    const MAX_LENGTH_OF_MESSAGE = 0;
    
    protected $request;
    
    public function __construct(array $parameters = array())
    {
        $this->request = new Request();
        $this->defineRequestProperties();
        $this->setParameters($parameters);
    }
    
    public static function getId()
    {
        $classname = get_called_class();
        return preg_replace('/^.*\\\\([A-Za-z]+)Entry$/', "$1", $classname);
    }
    
    public static function getServiceId()
    {
        return preg_replace(
                '/^([A-Za-z]+)(User|Forum|Group|Page)$/',
                "$1",
                self::getId()
        );
    }
    
    public static function getPublisherScopes()
    {
        return array();
    }
    
    public function setBody(array $body)
    {
        $this->validateBody($body);
        $this->request->setBody($body);
    }
    
    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
    
    protected function setParameters(array $parameters)
    {
        // default
    }
    
    // abstract methods
    
    protected abstract function defineRequestProperties();
    
    protected abstract function validateBody(array $body);
}