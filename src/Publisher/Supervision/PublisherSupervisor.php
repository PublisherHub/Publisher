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
    
    /**
     * The first part of the camelCase Entry ID.
     */
    const SERVICE_PATTERN = '/^([A-Z][a-z]+).+$/';
    
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
    
    // implementation of BaseEntryHelperInterface
    
    /**
     * @{inheritdoc}
     */
    public function getServiceId(string $entryId)
    {
        //preg_match(self::SERVICE_PATTERN, $entryId, $matches);
        
        if (preg_match(self::SERVICE_PATTERN, $entryId, $matches) &&
            in_array($matches[1], $this->getServices())
        ) {
            return $matches[1];
        }
        
        throw new EntryNotFoundException(
            "Service Id for Entry '{$entryId}' not found."
        );
    }
    
    /**
     * @{inheritdoc}
     */
    public function getEntryClass(string $entryId)
    {
        $service = $this->getServiceId($entryId);
        $class = sprintf(self::ENTRY_PATTERN, $service, $entryId);
        
        $this->checkExists($class, 'Entry');
        
        return $class;
    }
    
    /**
     * @{inheritdoc}
     */
    public function getSelectorDefinitionClass(string $entryId)
    {
        $service = $this->getServiceId($entryId);
        $class = sprintf(self::SELECTOR_DEFINITION_PATTERN, $service, $entryId);
        
        $this->checkExists($class, 'Selector', 'SelectorDefinition');
        
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
        
        $this->checkExists($class, 'Mode');
        
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
    protected function checkExists(
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
