<?php

namespace Publisher\Requestor;

use Publisher\Entry\EntryInterface;
use Publisher\Requestor\RequestorInterface;

interface RequestorFactoryInterface
{
    /**
     * Creates an instance of RequestorInterface
     * based on a service name and optional scopes.
     * 
     * @param string $serviceName e.g. 'Facebook', 'Google'
     * @param array $scopes
     * 
     * @return RequestorInterface
     */
    public function create(string $serviceName, array $scopes = array());
    
    /**
     * Creates an instance of RequestorInterface
     * based on an EntryInterface.
     * 
     * @param EntryInterface $entry
     * 
     * @return RequestorInterface
     */
    public function createByEntry(EntryInterface $entry);
    
}
