<?php

namespace Publisher\Monitoring;

use Publisher\Monitoring\MonitorInterface;
use Publisher\Monitoring\Exception\UnregisteredEntryException;

class Monitor implements MonitorInterface
{    

    /**
     * @var array
     */
    protected $status;

    
    /**
     * @param array $initialStatus
     */
    public function __construct(array $initialStatus = [])
    {
        $this->initStatus($initialStatus);
    }
    
    /**
     * @inheritDoc
     */
    public function monitor(string $entryId)
    {
        if (!$this->issetEntry($entryId)) {
            $this->monitorEntry($entryId);
        }
    }

    /**
     * @inheritDoc
     */
    public function setStatus(string $entryId, bool $success)
    {
        $this->checkEntryExists($entryId);
        
        $this->setEntryStatus($entryId, $success);
    }

    /**
     * @inheritDoc
     */
    public function executed(string $entryId)
    {
        $this->checkEntryExists($entryId);
        
        return ($this->status[$entryId] !== null);
    }
    
    /**
     * @inheritDoc
     */
    public function finished()
    {
        $status = $this->getStatus();
        
        foreach ($status as $entryId => $success) {
            if ($success === null) {
                return false;
            }
        }
        
        return true;

    }
    
    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * @inheritDoc
     */
    public function clearStatus()
    {
        $this->initStatus();
    }
    
    /**
     * Initializes the status to prepare for a new monitoring.
     * 
     * @return void
     */
    protected function initStatus(array $initialStatus = [])
    {
        $this->status = $initialStatus;
    }
    
    /**
     * Registers the Entry with the id of $entryId for the current monitoring.
     * 
     * @param string $entryId
     */
    protected function monitorEntry(string $entryId)
    {
        $this->setEntryStatus($entryId, null);
    }
    
    /**
     * Sets the status fo a registered Entry.
     * 
     * @param string $entryId
     * 
     * @param bool|null $status
     */
    protected function setEntryStatus(string $entryId, bool $status = null)
    {
        $this->status[$entryId] = $status;
    }
    
    /**
     * Verifies that an Entry with the id $entryId is currently monitored.
     * Throws an Exception if it is not registered.
     * 
     * @param string $entryId
     * 
     * @throws UnregisteredEntryException
     */
    protected function checkEntryExists(string $entryId)
    {
        if (!$this->issetEntry($entryId)) {
            throw new UnregisteredEntryException("$entryId is not registered.");
        }
    }
    
    /**
     * Returns true if an Entry with the id $entryId is already registered.
     * 
     * @param string $entryId
     * 
     * @return bool
     */
    protected function issetEntry(string $entryId)
    {
        return array_key_exists($entryId, $this->status);
    }

}
