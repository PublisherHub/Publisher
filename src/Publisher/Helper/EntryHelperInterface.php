<?php

namespace Publisher\Helper;

use Publisher\Helper\BaseEntryHelperInterface;

interface EntryHelperInterface extends BaseEntryHelperInterface
{
    
    public function checkIsEntryId(string $id);
    
    public function getPublisherScopes(string $entryId);
    
    public function getMaxMessageLength(string $entryId);
    
}
