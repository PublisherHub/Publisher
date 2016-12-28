<?php

namespace Publisher\Supervision;

interface PublisherSupervisorInterface
{
    
    /**
     * Checks if all listed entries and modes can be loaded successfully.
     * Returns an empty array() on success.
     * Otherwise it returns an array with the missing entries and/or modes.
     * 
     * Example:
     * array(
     *  'entries' => array(...),
     *  'modes' => array(...),
     * )
     * 
     * @return array
     */
    public function checkConfig();
    
    /**
     * Returns the ids of the configured services
     * 
     * @return array
     */
    public function getServices();
    
    /**
     * Returns the configured subtypes of each service
     * 
     * Example: If we have following configuration
     *  $config = array(
     *      'entries' =>
     *          'Facebook' => array('User', 'Page')
     *  );
     * getEntryIds('Facebook') will return
     *  array('User', 'Page')
     * 
     * @return array
     */
    public function getEntrySubtypes();
    
    
    /**
     * Returns the IDs of all available Entries of $service.
     * 
     * Example: If we have following configuration
     *  $config = array(
     *      'entries' =>
     *          'Facebook' => array('User', 'Page')
     *  );
     * getEntryIds('Facebook') will return
     *  array('FacebookUser', 'FacebookPage')
     * 
     * @param string $serviceId
     * @return array
     */
    public function getEntryIds(string $serviceId);
    
    /**
     * Returns all configured modes.
     * That doesn't mean that each entry supports this mode.
     * 
     * @return array
     */
    public function getAllModes();
    
}