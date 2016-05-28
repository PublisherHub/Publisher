<?php

namespace Publisher\Entry\OAuth2;

use Publisher\Entry\EntryInterface as BaseEntryInterface;

interface EntryInterface extends BaseEntryInterface
{
    
    public static function getPublisherScopes();
}