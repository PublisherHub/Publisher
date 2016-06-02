<?php

namespace Publisher\Monitoring;

use Publisher\Monitoring\MonitoringInterface;
use Publisher\Helper;
use Publisher\Monitoring\Exception\UnregisteredEntryException;

class Monitor implements MonitoringInterface
{
    
    /** @var string*/
    protected $statusKey;
    /** @var array*/
    protected $status;
    
    public function __construct(string $statusKey = 'PublisherMonitor', bool $startSession = false)
    {
        $this->statusKey = $statusKey;
        
        if ($startSession) {
            session_start();
        }
        
        $this->status = $this->getCurrentStatus();
        var_dump($this->status);
    }
    
    /**
     * @{inheritdoc}
     */
    public function monitor(string $entryName)
    {
        if (!array_key_exists($entryName, $this->status)) {
            $this->status[$entryName] = null;
        }
    }
    
    /**
     * @{inheritdoc}
     */
    public function setStatus(string $entryName, bool $success)
    {
        $this->checkEntryExists($entryName);
        
        $this->status[$entryName] = $success;
        $this->syncStatus($entryName, $success);
    }
    
    /**
     * @{inheritdoc}
     */
    public function executed(string $entryName)
    {
        $this->checkEntryExists($entryName);
        
        return ($this->status[$entryName] !== null);
    }
    
    public function finished()
    {
        $finished = true;
        
        foreach ($this->status as $entryName => $success) {
            $finished = $finished && ($success !== null);
        }
        
        return $finished;
    }
    
    /**
     * @{inheritdoc}
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    public function clearStatus()
    {
        if (isset($_SESSION[$this->statusKey])) {
            unset($_SESSION[$this->statusKey]);
        }
    }
    
    protected function getCurrentStatus()
    {
        if (!isset($_SESSION[$this->statusKey])) {
            $_SESSION[$this->statusKey] = array();
        }
        
        return $_SESSION[$this->statusKey];
    }
    
    /**
     * @param string $entryName
     * 
     * @return void
     */
    protected function checkEntryExists($entryName)
    {
        if (!array_key_exists($entryName, $this->status)) {
            throw new UnregisteredEntryException("$entryName is not registered.");
        }
    }
    
    /**
     * Synchronize Monitor status with the session variable.
     * 
     * @return void
     */
    protected function syncStatus(string $entryName, bool $success)
    {
        //$_SESSION[$this->statusKey][$entryName] = $success;
        $_SESSION[$this->statusKey] = $this->status;
    }
}
