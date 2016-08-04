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
     * Create instances of EntryInterface
     * or $entryData given by the users input.
     * The input should be validated before
     * it s given to the publishing manager.
     * 
     * Example $entryData:
     * 
     * $entryData = array(
     *  0 => array(
     *      'entry' => // entry id ,
     *      'content' => array( // specific content based on the mode ),
     *      'mode' => // mode id
     *  ),
     *  1 => array(
     *      'entry' => 'TwitterUser',
     *      'content' => array( 'message' => 'foo', ... ),
     *      'mode' => 'Recommendation'
     *  ),
     *  ...
     * );
     * 
     * @param array $entryData
     */
    public function setupEntries(array $entryData);
    
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
     * Returns true if all posts where executed
     * (either succeded or failed).
     * 
     * @return bool
     */
    public function hasFinished();
    
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
     * post entry with id FacebookPage // success
     * clearStatus()
     * post entry with id FacebookUser // success
     * post entry with id FacebookUser // will be ignored
     * ...
     */
    public function clearStatus();
    
}
