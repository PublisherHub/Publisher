<?php

namespace Publisher\Entry\Factory;

use Publisher\Entry\Factory\EntryFactoryInterface;
use Publisher\Helper\EntryHelperInterface;

class EntryFactory implements EntryFactoryInterface
{
    
    protected $entryHelper;
    
    public function __construct(EntryHelperInterface $entryHelper)
    {
        $this->entryHelper = $entryHelper;
    }
    
    public function getEntry($entryId, array $parameters = array())
    {
        $entryClass = $this->entryHelper->getEntryClass($entryId);
        
        return new $entryClass($parameters);
    }
}