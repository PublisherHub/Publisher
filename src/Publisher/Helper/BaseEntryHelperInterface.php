<?php

namespace Publisher\Helper;

interface BaseEntryHelperInterface
{
    
    public function getServiceId(string $entryId);
    
    public function getEntryClass(string $entryId);
    
    public function getSelectorClass(string $entryId);
    
    public function getModeClass(string $modeId);
    
}
