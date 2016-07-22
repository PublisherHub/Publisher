<?php

namespace Publisher\Supervision;

use Publisher\Supervision\PublisherSupervisorInterface;
use Publisher\Helper\EntryHelperInterface;

use Publisher\Entry\Exception\EntryNotFoundException;

/**
 * Configure the entries and modes that you want to use.
 * Check if all classes can be found.
 * 
 * You can use specific configurations e.g. for each user.
 * 
 * If you want to add more 
 */
class PublisherSupervisor implements PublisherSupervisorInterface, EntryHelperInterface
{
    
    const ENTRY_NAMESPACE = '\\Publisher\\Entry\\';
    const MODE_NAMESPACE = '\\Publisher\\Mode\\';
    
    protected $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function checkConfig()
    {
        $notFound = array();
        $notFound['entries'] = $this->getMissingEntries();
        $notFound['modes'] = $this->getMissingModes();
        
        return $notFound;
    }
    
    public function getServices()
    {
        return array_keys($this->config['entryIds']);
    }
    
    public function getEntrySubTypes()
    {
        return $this->config['entryIds'];
    }
    
    /**
     * Returns all configured modes.
     * That doesn't mean that each entry supports this mode.
     * 
     * @return array
     */
    public function getAllModes()
    {
        return $this->config['modes'];
    }
    
    protected function getMissingEntries()
    {
        $entrySubTypes = $this->getEntrySubTypes();
        
        $classes = array();
        foreach ($entrySubTypes as $service => $subTypes) {
            foreach ($subTypes as $subType) {
                $classes[] = self::ENTRY_NAMESPACE.$service.'\\'.$service.$subType.'Entry';
            }
        }
        
        return $this->checkExists($classes);
    }
    
    protected function getMissingModes()
    {
        $modes = $this->config['modes'];
        
        $interfaces = array();
        $classes = array();
        foreach ($modes as $mode) {
            $interfaces[] = self::MODE_NAMESPACE.$mode.'\\'.$mode.'Interface';
            $classes[] = self::MODE_NAMESPACE.$mode.'\\'.$mode.'Manager';
        }
        
        $notFound = $this->checkExists($classes);
        
        return array_merge($notFound, $this->checkExists($interfaces, 'interface'));
    }
    
    protected function checkExists(array $names, string $type = 'class')
    {
        $notFound = array();
        $checkExists = $type.'_exists';
        
        foreach ($names as $name) {
            if (!$checkExists($name)) {
                $notFound[] = $name;
            }
        }
        
        return $notFound;
    }
    
    // implementation of EntryHelper
    
    public function getServiceId(string $entryId)
    {
        $serviceId =  preg_replace(
            '/^([A-Za-z]+)(User|Forum|Group|Page)$/',
            "$1",
            $entryId
        );
        
        if (is_null($serviceId) || $serviceId === $entryId) {
            throw new EntryNotFoundException("'{$entryId}' is no valid entry id.");
        } else {
            return $serviceId;
        }
    }
    
    public function checkIsEntryId(string $entryId)
    {
        $serviceId = $this->getServiceId($entryId);
        $entryIds = $this->getEntryIds(
                $serviceId,
                $this->config['entryIds'][$serviceId]
        );
        
        if (!in_array($entryId, $entryIds)) {
            throw new EntryNotFoundException("$entryId is not configured.");
        }
    }
    
    public function getPublisherScopes(string $entryId)
    {
        $class = $this->getEntryClass($entryId);
        
        return $class::getPublisherScopes();
    }
    
    public function getEntryClass(string $entryId)
    {
        return $this->getClass($entryId, 'Entry');
    }
    
    public function getSelectorClass(string $entryId)
    {
        return $this->getClass($entryId, 'Selector', '\\Selector\\');
    }
    
    protected function getEntryIds(string $service, array $subTypes)
    {
        $entryIds = array();
        foreach ($subTypes as $subType) {
            $entryIds[] = $service.$subType;
        }
        
        return $entryIds;
    }
    
    protected function getClass(
            string $entryId,
            string $type,
            string $prefix = '\\'
    ) {
        $service = $this->getServiceId($entryId);
        
        $class = self::ENTRY_NAMESPACE.$service.$prefix; // @todo too static
        $class .= $entryId.$type;
        
        $this->checkClassExists($class, $type);
        
        return $class;
    }
    
    protected function checkClassExists(string $class, string $type)
    {
        if (!class_exists($class)) {
            $exceptionClass = '\\Publisher\\'.$type.'\\Exception\\';
            $exceptionClass .= $type.'NotFoundException';
            throw new $exceptionClass("Unknown $type: $class");
        }
    }
}
