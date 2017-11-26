<?php

namespace Publisher\Entry\Factory;

use Publisher\Entry\Factory\EntryFactoryInterface;
use Publisher\Helper\EntryHelperInterface;

class EntryFactory implements EntryFactoryInterface
{
    
    /**
     * @var EntryHelperInterface
     */
    protected $entryHelper;
    
    /**
     * @param EntryHelperInterface $entryHelper
     */
    public function __construct(EntryHelperInterface $entryHelper)
    {
        $this->entryHelper = $entryHelper;
    }
    
    /**
     * @inheritDoc
     */
    public function getEntry($entryId, array $parameters = [], array $body = [])
    {
        $entryClass = $this->entryHelper->getEntryClass($entryId);
        $entry = new $entryClass($parameters);
        
        if (!empty($body)) {
            $entry->setBody($body);
        }
        
        return $entry;
    }
}