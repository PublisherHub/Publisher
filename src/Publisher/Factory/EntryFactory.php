<?php

namespace Publisher\Factory;

use Publisher\Exception\UnknownEntryException;

class EntryFactory
{
    /** GET VERSION BY ENTRY BENOETIGT, CLASS_EXISTS WONT DO IT*/
    public static function getEntry($entryName, array $parameters = array())
    {
        if (class_exists('\\Publisher\\Entry\\OAuth1\\'.$entryName)) {
            $entryName = '\\Publisher\\Entry\\OAuth1\\'.$entryName;
        } elseif (class_exists('\\Publisher\\Entry\\OAuth2\\'.$entryName)) {
            $entryName = '\\Publisher\\Entry\\OAuth2\\'.$entryName;
        } else {
            throw new UnknownEntryException("Unknown Entry: $entryName");
        }
        
        return new $entryName($parameters);
        
    }
}