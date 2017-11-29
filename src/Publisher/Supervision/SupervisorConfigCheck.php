<?php

namespace Publisher\Supervision;

use Publisher\Supervision\PublisherSupervisorInterface;

/**
 * Class to check the configuration of PublisherSupervisor.
 */
class SupervisorConfigCheck
{
    
    /**
     * Checks if all listed entries and modes can be loaded successfully.
     * Returns an array with the missing entries, modes and entities.
     * 
     * Example:
     * [
     *  'entries' => [...],
     *  'modes' => [...],
     * ]
     * 
     * @return array
     */
    public static function checkConfig(PublisherSupervisorInterface $supervisor)
    {
        $notFound = [];
        $notFound['entries'] = self::getMissingEntries($supervisor);
        $notFound['modes'] = self::getMissingModes($supervisor);
        $notFound['entities'] = self::getMissingEntities($supervisor);
        
        return $notFound;
    }
    
    /**
     * Returns the Entry classes that weren't found.
     * 
     * @param PublisherSupervisorInterface $supervisor
     * 
     * @return string[]
     */
    protected static function getMissingEntries(PublisherSupervisorInterface $supervisor)
    {
        $entrySubtypes = $supervisor->getEntrySubtypes();
        
        $classes = [];
        foreach ($entrySubtypes as $service => $subtypes) {
            foreach ($subtypes as $subtype) {
                $classes[] = sprintf($supervisor::ENTRY_PATTERN, $service, $service . $subtype);
            }
        }
        
        return self::getMissingClasses($classes);
    }
    
    
    /**
     * Returns the Mode classes that weren't found.
     * 
     * @param PublisherSupervisorInterface $supervisor
     * 
     * @return string[]
     */
    protected static function getMissingModes(PublisherSupervisorInterface $supervisor)
    {
        $modes = $supervisor->getAllModes();
        
        $classes = [];
        foreach ($modes as $mode) {
            $classes[] = sprintf($supervisor::ABSTRACT_MODE_PATTERN , $mode, $mode);
        }
        
        // @todo check which Entries have a configured ModeEntity
        
        return self::getMissingClasses($classes);
    }
    
    /**
     * Checks if each Entry has an Entity for all Modes.
     * 
     * @param PublisherSupervisorInterface $supervisor
     * 
     * @return string[]
     */
    protected static function getMissingEntities(PublisherSupervisorInterface $supervisor)
    {
        $modes = $supervisor->getAllModes();
        $entrySubtypes = $supervisor->getEntrySubtypes();
        
        $classes = [];
        foreach ($entrySubtypes as $service => $subtypes) {
            foreach ($modes as $mode) {
                foreach ($subtypes as $subtype) {
                    $classes[] = sprintf(
                        $supervisor::MODE_PATTERN,
                        $service,
                        $mode,
                        $service . $subtype . $mode
                    );
                }
            }
        }
        
        return self::getMissingClasses($classes);
    }
    
    /**
     * Returns the classes stored in $classes
     * that couldn't be found. 
     * 
     * @param string[] $classes
     * 
     * @return array
     */
    protected static function getMissingClasses(array $classes)
    {
        $notFound = [];
        
        foreach ($classes as $class) {
            if (!class_exists($class)) {
                $notFound[] = $class;
            }
        }
        
        return $notFound;
    }
    
}
