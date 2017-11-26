<?php

namespace Publisher\Entry\Factory;

use Publisher\Entry\EntryInterface;

interface EntryFactoryInterface
{
    
    /**
     * @param type  $entryId
     * @param array $parameters
     * @param array $body
     * 
     * @return EntryInterface
     */
    public function getEntry($entryId, array $parameters = [], array $body = []);
}