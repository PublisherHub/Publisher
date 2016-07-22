<?php

namespace Publisher\Supervision;

interface PublisherSupervisorInterface
{
    
    /**
     * Checks if all listed entries and modes can be loaded successfully.
     * Returns an empty array() on success.
     * Otherwise it returns an array with the missing entries and/or modes.
     * 
     * Example:
     * array(
     *  'entries' => array(...),
     *  'modes' => array(...),
     * )
     * 
     * @return array
     */
    public function checkConfig();
    
}