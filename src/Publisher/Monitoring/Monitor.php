<?php

namespace Publisher\Monitoring;

use Publisher\Monitoring\MonitoringInterface;
use Publisher\Monitoring\Exception\UnregisteredEntryException;

class Monitor implements MonitoringInterface
{
    
    /** @var array*/
    protected $results;
    
    public function __construct(array $updatedResults = array())
    {
        $this->results = $updatedResults;
    }
    
    /**
     * @{inheritdoc}
     */
    public function monitor($entryName)
    {
        if (!array_key_exists($entryName, $this->results)) {
            $this->results[$entryName] = null;
        }
    }
    
    /**
     * @{inheritdoc}
     */
    public function setResult($entryName, bool $success)
    {
        $this->checkEntryExists($entryName);
        
        $this->results[$entryName] = $success;
        
    }
    
    /**
     * @{inheritdoc}
     */
    public function executed($entryName)
    {
        $this->checkEntryExists($entryName);
        
        return ($this->results[$entryName] !== null);
    }
    
    /**
     * @{inheritdoc}
     */
    public function getResults()
    {
        return $this->results;
    }
    
    /**
     * @param string $entryName
     * 
     * @return void
     */
    protected function checkEntryExists($entryName)
    {
        if (!array_key_exists($entryName, $this->results)) {
            throw new UnregisteredEntryException("$entryName is not registered.");
        }
    }
}
