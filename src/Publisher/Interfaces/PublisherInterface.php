<?php

namespace Publisher\Interfaces;

use Publisher\Entry\EntryInterface;

interface PublisherInterface
{
    
    /**
     * Makes a request based on the data of $entry.
     * 
     * @param EntryInterface $entry
     * @return string
     */
    public function publish(EntryInterface $entry);
    
}