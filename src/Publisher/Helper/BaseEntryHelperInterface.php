<?php

namespace Publisher\Helper;

interface BaseEntryHelperInterface
{
    
    /**
     * Returns the service ID of $entryId.
     * 
     * Example:
     * getServiceId('TwitterUser') returns 'Twitter'
     * 
     * @param string $entryId ID of an Entry
     * 
     * @return string
     */
    public function getServiceId(string $entryId);
    
    /**
     * Returns the full class name of the Entry with the ID $entryId.
     * 
     * @param string $entryId ID of an Entry
     * 
     * @return string
     */
    public function getEntryClass(string $entryId);
    
    /**
     * Returns the full class name of the SelectorDefinition
     * that belongs to an Entry with the ID $entryId.
     * 
     * @param string $entryId ID of an Entry
     * 
     * @return string
     */
    public function getSelectorDefinitionClass(string $entryId);
    
    /**
     * Returns the full class name of a Mode Entity.
     * If $entryId is 'Abstract' it should return the base Entity.
     * 
     * @param string $modeId ID of an Mode
     * @param string $entryId ID of an Entry
     * 
     * @return string
     */
    public function getModeClass(string $modeId, string $entryId = 'Abstract');
    
}
