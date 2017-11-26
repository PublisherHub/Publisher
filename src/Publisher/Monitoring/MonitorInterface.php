<?php

namespace Publisher\Monitoring;

use Publisher\Monitoring\Exception\UnregisteredEntryException;

interface MonitorInterface
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
     * @throws UnregisteredEntryException
     * 
     * @return void
     */
    public function setStatus(string $entryId, bool $success);
    
    /**
     * Returns whether or not the entry was executed and therefore got a result.
     * Returns true if the entry got a status unlike null.
     * 
     * @param type $entryId
     * 
     * @throws UnregisteredEntryException
     * 
     * @return bool
     */
    public function executed(string $entryId);
    
    /**
     * Returns true if each registred Entry has a saved status unlike null.
     * 
     * @return bool
     */
    public function finished();
    
    /**
     * Returns the outcome for each entry.
     * 
     * If the status is null then the Entry is only registered.
     * A value of true or false marks the success or failure of e.g. a request.
     * 
     * Example:
     * ['entry1' => true, 'entry2' => false, 'entry3' => null, ...]
     * 
     * @return array
     */
    public function getStatus();
    
    /**
     * Resets the status completely.
     * It'll be possible to start a new monitoring after that.
     * 
     * @return void
     */
    public function clearStatus();
    
}
