<?php

namespace Publisher\Helper;

use Publisher\Entry\EntryInterface;

class EntryHelper
{
    
    public static function isEntryName($name)
    {
        if (class_exists('\\Publisher\\Entry\\OAuth1\\'.$name) ||
            class_exists('\\Publisher\\Entry\\OAuth2\\'.$name)
        ) {
            return true;
        } else {
            false;
        }
    }
    
    public static function getServiceName(EntryInterface $entry)
    {
        return preg_replace('/^(.+)(User|Forum|Group)Entry$/', "$1", $entry->getName());
    }
    
    public static function getPublisherScopes(EntryInterface $entry)
    {
        if ($entry instanceof \Publisher\Entry\OAuth2\EntryInterface) {
            return $entry::getPublisherScopes();
        } else {
            return array();
        }
    }
    
}