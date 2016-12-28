<?php

namespace Publisher\Mode;

use Publisher\Mode\BodyGeneratorInterface;

/**
 * Base class for EntryModeEntity classes to map the user input based on a mode
 * to the required body parameters of an entry type.
 * 
 * If a mode allows a user to choose a message and an url
 * foreach entry in the same way, then this content needs to be mapped
 * to the specific body parameters of the entry type.
 * For example FacebookPage entries offer the body parameters 'message'
 * and 'link'. So we can just put other message into 'message' and url in 'link'.
 * In this case the TwitterUser entries only offer the 'status'
 * so that we would combine the message and url as a single string in 'status'.
 */
abstract class AbstractEntryModeEntity implements BodyGeneratorInterface
{
    
    /**
     * @param array $content content data from a mode
     */
    public abstract function __construct(array $content = array());
    
}