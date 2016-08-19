<?php

namespace Publisher\Supervision;

use Publisher\Supervision\PublisherSupervisorInterface;
use Publisher\Helper\BaseEntryHelperInterface;
use Publisher\Entry\Exception\EntryNotFoundException;

/**
 * Configure the entries and modes that you want to use.
 * Check if all classes can be found.
 * 
 * You can use specific configurations e.g. for each user.
 * 
 * If you want to add more 
 */
class PublisherSupervisor implements
    PublisherSupervisorInterface,
    BaseEntryHelperInterface
{
    
    const ENTRY_NAMESPACE = '\\Publisher\\Entry\\';
    const MODE_NAMESPACE = '\\Publisher\\Mode\\';
    const SERVICE_PATTERN = '/^([A-Za-z]+)(User|Forum|Group|Page)$/';
    
    protected $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
    }
    
    // implementation of PublisherSupervisorInterface
    
    /**
     * @{inheritdoc}
     */
    public function checkConfig()
    {
        $notFound = array();
        $notFound['entries'] = $this->getMissingEntries();
        $notFound['modes'] = $this->getMissingModes();
        
        return $notFound;
    }
    
    /**
     * @{inheritdoc}
     */
    public function getServices()
    {
        return array_keys($this->config['entries']);
    }
    
    /**
     * @{inheritdoc}
     */
    public function getEntrySubtypes()
    {
        return $this->config['entries'];
    }
    
    /**
     * @{inheritdoc}
     */
    public function getAllModes()
    {
        return $this->config['modes'];
    }
    
    /**
     * @{inheritdoc}
     */
    public function getEntryIds(string $serviceId)
    {
        if (isset($this->config['entries'][$serviceId])) {
            
            $subtypes = $this->config['entries'][$serviceId];
            $entryIds = array();
            foreach ($subtypes as $subtype) {
                $entryIds[] = $serviceId.$subtype;
            }
            
            return $entryIds;
            
        } else {
            
            return array();
        }
    }
    
    protected function getMissingEntries()
    {
        $entrySubtypes = $this->getEntrySubtypes();
        
        $classes = array();
        foreach ($entrySubtypes as $service => $subtypes) {
            foreach ($subtypes as $subtype) {
                $classes[] = self::ENTRY_NAMESPACE.$service.'\\'.$service.$subtype.'Entry';
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
            $classes[] = self::MODE_NAMESPACE.$mode.'\\'.$mode.'Mode';
        }
        
        $notFound = $this->checkExists($classes);
        
        return array_merge($notFound, $this->checkExists($interfaces, 'interface'));
    }
    
    /**
     * Returns the classes or interfaces stored in $names
     * that couldn't be found. 
     * 
     * @param array $names classes or interfaces
     * @param string $type 'class' or 'interface'
     * 
     * @return array
     */
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
    
    // implementation of BaseEntryHelperInterface
    
    /**
     * @{inheritdoc}
     */
    public function getServiceId(string $entryId)
    {
        $serviceId = preg_replace(self::SERVICE_PATTERN, "$1", $entryId);
        
        $servicesIds = $this->getServices();
        
        if (in_array($serviceId, $servicesIds)) {
            return $serviceId;
        } elseif (is_null($serviceId) || $serviceId === $entryId) {
            $message = "'{$entryId}' is no valid entry id.";
        } else {
            $message = "'{$entryId}' is not configured.";
        }
        throw new EntryNotFoundException($message);
    }
    
    /**
     * @{inheritdoc}
     */
    public function getEntryClass(string $entryId)
    {
        return $this->getClass($entryId, 'Entry');
    }
    
    /**
     * @{inheritdoc}
     */
    public function getSelectorClass(string $entryId)
    {
        return $this->getClass($entryId, 'Selector', '\\Selector\\');
    }
    
    /**
     * @{inheritdoc}
     */
    public function getModeClass(string $modeId)
    {
        $class = self::MODE_NAMESPACE.$modeId.'\\'.$modeId.'Mode';
        
        $this->checkClassExists($class, 'Mode');
        
        return $class;
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
