<?php

namespace Publisher\Storage;

interface StorageInterface
{
    
    /**
     * @param string $clientId
     * 
     * @return void
     */
    public function registerClient(string $clientId);
    
    /**
     * Returns the value stored as $key of $clientId.
     * 
     * @param string $clientId
     * @param string $key
     * 
     * @return mixed
     */
    public function get(string $clientId, string $key);
    
    /**
     * Saves $value as $key of $clientId.
     * 
     * @param string $clientId
     * @param string $key
     * @param type $value
     * 
     * @return void
     */
    public function set(string $clientId, string $key, $value);
    
    /**
     * Removes the storage of $clientId.
     * 
     * @param string $clientId
     * 
     * @return void
     */
    public function clear(string $clientId);
    
    /**
     * Removes the complete Storage.
     * 
     * @return void
     */
    public function clearAll();
    
}