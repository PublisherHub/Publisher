<?php

namespace Publisher\Storage;

use Publisher\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;

class Session implements StorageInterface
{
    
    protected $session;
    protected $baseSessionKey;
    protected $sessionKey;
    
    public function __construct(
            SessionInterface $session,
            string $sessionKey = 'ClientStorage',
            string $baseSessionKey = 'Publisher'
    ) {
        $this->session = $session;
        $this->baseSessionKey = $baseSessionKey;
        $this->sessionKey = $this->baseSessionKey.'/'.$sessionKey;
        
        $bag = new NamespacedAttributeBag();
        $this->session->registerBag($bag);
    }
    
    /**
     * @{inheritdoc}
     */
    public function registerClient(string $clientId)
    {
        if (!$this->hasClientStorage($clientId)) {
            $this->session->set($this->getSessionSubKey($clientId), array());
        }
    }
    
    /**
     * @{inheritdoc}
     */
    public function get(string $clientId, string $key)
    {
        return $this->session->get(
            $this->getClientSubKey($clientId, $key),
            null
        );
    }
    
    /**
     * @{inheritdoc}
     */
    public function set(string $clientId, string $key, $value)
    {
        $this->session->set(
            $this->getClientSubKey($clientId, $key),
            $value
        );
    }
    
    /**
     * @{inheritdoc}
     */
    public function clear(string $clientId)
    {
        $this->session->remove($this->getSessionSubKey($clientId));
    }
    
    /**
     * @{inheritdoc}
     */
    public function clearAll()
    {
        $this->session->remove($this->sessionKey);
    }
    
    protected function hasClientStorage(string $clientId)
    {
        return $this->session->has($this->getSessionSubKey($clientId));
    }
    
    protected function getClientSubKey(string $clientId, string $subkey)
    {
        return $this->getSessionSubKey($clientId).'/'.$subkey;
    }
    
    protected function getSessionSubKey(string $subkey)
    {
        return $this->sessionKey.'/'.$subkey;
    }
}