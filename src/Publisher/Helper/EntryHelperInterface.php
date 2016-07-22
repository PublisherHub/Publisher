<?php

namespace Publisher\Helper;

interface EntryHelperInterface
{
    
    public function getServiceId(string $entryId);
    
    public function checkIsEntryId(string $id);
    
    public function getPublisherScopes(string $entryId);
    
    public function getEntryClass(string $entryId);
    
    public function getSelectorClass(string $entryId);
    
}
