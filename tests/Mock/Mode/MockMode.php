<?php

namespace Publisher\Mode\Mock;

use Publisher\Mode\ModeInterface;
use Publisher\Entry\EntryInterface;

class MockMode implements ModeInterface
{
    
    public static function checkContent(array $content)
    {
        
    }
    
    public static function checkImplementsMode(EntryInterface $entry)
    {
        
    }
    
    public static function fillEntry(EntryInterface $entry, array $content)
    {
        
    }
    
}