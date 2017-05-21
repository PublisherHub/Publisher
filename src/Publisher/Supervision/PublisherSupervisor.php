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
 */
class PublisherSupervisor implements
    PublisherSupervisorInterface,
    BaseEntryHelperInterface
{
    /**
     * [1] service id [2] entry id
     */
    const ENTRY_PATTERN = '\\Publisher\\Entry\\%s\\%sEntry';
    
    /**
     * [1] service id [2] entry id
     */
    const SELECTOR_DEFINITION_PATTERN = '\\Publisher\\Entry\\%s\\Selector\\%sSelectorDefinition';
    
    /**
     * [1] service id [2] mode id [3] entry id . mode id
     */
    const MODE_PATTERN = '\\Publisher\\Entry\\%s\\Mode\\%s\\%s';
    
    /**
     * [1] mode id [2] mode id
     */
    const ABSTRACT_MODE_PATTERN = '\\Publisher\\Mode\\%s\\Abstract%s';
    
    /**
     * [1] type id [2] type id
     */
    const NOT_FOUND_EXCEPTION_PATTERN = '\\Publisher\\%s\\Exception\\%sNotFoundException';
    
    // @todo move configuration
    const SERVICE_PATTERN = '/^([A-Za-z]+)(User|Forum|Group|Page)$/';
    
    /**
     * @var array
     */
    protected $config;
    
    /**
     * @param array $config
     */
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
        $notFound['entities'] = $this->getMissingEntities();
        
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
                $entryIds[] = $serviceId . $subtype;
            }
            
            return $entryIds;
        }
        
        return array();
    }
    
    protected function getMissingEntries()
    {
        $entrySubtypes = $this->getEntrySubtypes();
        
        $classes = array();
        foreach ($entrySubtypes as $service => $subtypes) {
            foreach ($subtypes as $subtype) {
                $classes[] = sprintf(self::ENTRY_PATTERN, $service, $service . $subtype);
            }
        }
        
        return $this->getMissingClasses($classes);
    }
    
    protected function getMissingModes()
    {
        $modes = $this->config['modes'];
        
        $classes = array();
        foreach ($modes as $mode) {
            $classes[] = sprintf('\\Publisher\\Mode\\' . $mode . '\\Abstract' . $mode);
        }
        
        // @todo check which Entries have an ModeEntity
        
        return $this->getMissingClasses($classes);
    }
    
    /**
     * Checks if each Entry has an Entity for all Modes.
     * 
     * @return string[]
     */
    protected function getMissingEntities()
    {
        $modes = $this->getAllModes();
        $entrySubtypes = $this->getEntrySubtypes();
        
        $classes = array();
        foreach ($entrySubtypes as $service => $subtypes) {
            foreach ($modes as $mode) {
                foreach ($subtypes as $subtype) {
                    $classes[] = sprintf(
                        self::MODE_PATTERN,
                        $service,
                        $mode,
                        $service . $subtype . $mode
                    );
                }
            }
        }
        
        return $this->getMissingClasses($classes);
    }
    
    /**
     * Returns the classes stored in $classes
     * that couldn't be found. 
     * 
     * @param string[] $classes
     * 
     * @return array
     */
    protected function getMissingClasses(array $classes)
    {
        $notFound = array();
        
        foreach ($classes as $class) {
            if (!class_exists($class)) {
                $notFound[] = $class;
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
        $service = $this->getServiceId($entryId);
        $class = sprintf(self::ENTRY_PATTERN, $service, $entryId);
        
        $this->checkClassExists($class, 'Entry');
        
        return $class;
    }
    
    /**
     * @{inheritdoc}
     */
    public function getSelectorDefinitionClass(string $entryId)
    {
        $service = $this->getServiceId($entryId);
        $class = sprintf(self::SELECTOR_DEFINITION_PATTERN, $service, $entryId);
        
        $this->checkClassExists($class, 'Selector', 'SelectorDefinition');
        
        return $class;
    }
    
    /**
     * @{inheritdoc}
     */
    public function getModeClass(string $modeId, string $entryId = 'Abstract')
    {
        if ($entryId == 'Abstract') {
            $class = sprintf(self::ABSTRACT_MODE_PATTERN, $modeId, $modeId);
        } else {
            $service = $this->getServiceId($entryId);
            $class = sprintf(self::MODE_PATTERN, $service, $modeId, $entryId . $modeId);
        }
        
        $this->checkClassExists($class, 'Mode');
        
        return $class;
    }
    
    /**
     * 
     * @param string      $class
     * @param string      $type
     * @param string|null $typeClassName should be set, if it differs from $type
     * 
     * @throws \Exception
     */
    protected function checkClassExists(
        string $class,
        string $type,
        string $typeClassName = null
    ) {
        if (!class_exists($class)) {
            $exceptionClass = sprintf(
                self::NOT_FOUND_EXCEPTION_PATTERN,
                $type,
                $typeClassName ? $typeClassName : $type
            );
            throw new $exceptionClass("Unknown $type: $class");
        }
    }
    
}
