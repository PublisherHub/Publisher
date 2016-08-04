<?php

namespace Publisher\Mode;

use Publisher\Entry\EntryInterface;
use Publisher\Mode\Exception\InterfaceRequiredException;
use Publisher\Helper\Exception\MissingRequiredParameterException;

interface ModeInterface
{
    
    /**
     * Check if the entry even implemented the mode.
     * 
     * @param EntryInterface $entry
     * 
     * @return void
     * 
     * @throws InterfaceRequiredException
     */
    public static function checkImplementsMode(EntryInterface $entry);
    
    /**
     * Check if all required, mode specific parameters are set.
     * 
     * @param array $content
     * 
     * @return void
     * 
     * @throws MissingRequiredParameterException
     */
    public static function checkContent(array $content);
    
    /**
     * Set the content for $entry.
     * 
     * @param EntryInterface $entry
     * @param array $content
     * 
     * @return void
     * 
     * @throws InterfaceRequiredException
     */
    public static function fillEntry(EntryInterface $entry, array $content);
    
}
