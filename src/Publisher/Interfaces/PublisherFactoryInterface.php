<?php

namespace Publisher\Interfaces;

use Publisher\Entry\EntryInterface;

interface PublisherFactoryInterface
{
    
    /**
     * @param string $serviceName
     * @param array $scopes
     * @return \Publisher\Interfaces\PublisherInterface
     */
    public function getPublisher($serviceName, array $scopes = array());
    
    /**
     * @param EntryInterface $entry
     * @return \Publisher\Interfaces\PublisherInterface
     */
    public function getPublisherByEntry(EntryInterface $entry);
}