<?php

namespace Publisher\Manager;

/**
 * Publish (more than one) entry.
 * Prevent multiple identical posts by monitoring the success
 * and blocking multiple requests based on the entry id
 * until the status is cleared.
 */
interface PublishingManagerInterface
{
    
    /**
     * Publish all entries.
     * 
     * @return void
     */
    public function publishAll();
    
    /**
     * Get information about whether or not
     * the entries were published successfully.
     * 
     * Example return value:
     * array('FacebookPage' => true, 'TwitterUser' => false)
     * 
     * @return array
     */
    public function getStatus();
    
    /**
     * Before clearStatus() is executed it shouldn't be possible
     * to posts entries with the same entry id more than once.
     * 
     * After clearStatus() was executed it should be possible
     * to posts entries with the same entry id again.
     * 
     * Example:
     * post entry with id FacebookUser // success
     * post entry with id FacebookUser // will be ignored
     * clearStatus()
     * post entry with id FacebookUser // success
     * post entry with id FacebookUser // will be ignored
     * ...
     */
    public function clearStatus();
    
}
