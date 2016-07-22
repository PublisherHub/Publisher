<?php

namespace Publisher\Monitoring;

interface MonitoringInterface
{
    
    /**
     * Register an EntryInterface for monitoring.
     * 
     * @param string $entryId
     * 
     * @return void
     */
    public function monitor(string $entryId);
    
    /**
     * Marks the publishing as successful or failed.
     * 
     * @param string $entryId
     * @param bool $success
     * 
     * @throws \Publisher\Monitoring\Exception\UnregisteredEntryException
     * 
     * @return void
     */
    public function setStatus(string $entryId, bool $success);
    
    /**
     * Returns whether or not the entry was executed and therefore got a result.
     * Returns true if the entry got a result (result !== null).
     * 
     * @param type $entryId
     * 
     * @throws \Publisher\Monitoring\Exception\UnregisteredEntryException
     * 
     * @return bool
     */
    public function executed(string $entryId);
    
    /**
     * Returns true if every request was executed.
     * 
     * @return bool
     */
    public function finished();
    
    /**
     * Returns the outcome for each entry.
     * array('entry1' => true, 'entry2' => false, ...)
     * 
     * @return array
     */
    public function getStatus();
    
    /**
     * Resets the status for each entry
     * or deletes the status completely.
     * 
     * @return void
     */
    public function clearStatus();
    
}
