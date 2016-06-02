<?php

namespace Publisher\Monitoring;

interface MonitoringInterface
{
    
    /**
     * Register an EntryInterface for monitoring.
     * 
     * @param string $entryName
     * 
     * @return void
     */
    public function monitor(string $entryName);
    
    /**
     * Marks the publishing as successful or failed.
     * 
     * @param string $entryName
     * @param bool $success
     * 
     * @throws \Publisher\Monitoring\Exception\UnregisteredEntryException
     * 
     * @return void
     */
    public function setStatus(string $entryName, bool $success);
    
    /**
     * Returns whether or not the entry was executed and therefore got a result.
     * Returns true if the entry got a result (result !== null).
     * 
     * @param type $entryName
     * 
     * @throws \Publisher\Monitoring\Exception\UnregisteredEntryException
     * 
     * @return bool
     */
    public function executed(string $entryName);
    
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
     * @return void
     */
    public function clearStatus();
}
