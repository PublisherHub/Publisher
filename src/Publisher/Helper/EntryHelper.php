<?php

namespace Publisher\Helper;

use Publisher\Helper\EntryHelperInterface;
use Publisher\Helper\BaseEntryHelperInterface;

class EntryHelper implements EntryHelperInterface
{
    
    protected $baseHelper;
    
    public function __construct(BaseEntryHelperInterface $baseHelper)
    {
        $this->baseHelper = $baseHelper;
    }
    
    public function checkIsEntryId(string $entryId)
    {
        $entryClass = $this->getEntryClass($entryId);
    }
    
    public function getPublisherScopes(string $entryId)
    {
        $class = $this->getEntryClass($entryId);
        
        return $class::getPublisherScopes();
    }
    
    public function getMaxMessageLength(string $entryId)
    {
        $entryClass = $this->getEntryClass($entryId);
        
        return $entryClass::MAX_LENGTH_OF_MESSAGE;
    }
    
    public function getServiceId(string $entryId)
    {
        return $this->baseHelper->getServiceId($entryId);
    }
    
    public function getEntryClass(string $entryId)
    {
        return $this->baseHelper->getEntryClass($entryId);
    }
    
    public function getSelectorClass(string $entryId)
    {
        return $this->baseHelper->getSelectorClass($entryId);;
    }
    
    public function getModeClass(string $modeId)
    {
        return $this->baseHelper->getModeClass($modeId);
    }

}
