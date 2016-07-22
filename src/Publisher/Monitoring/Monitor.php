<?php

namespace Publisher\Monitoring;

use Publisher\Monitoring\MonitoringInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use Publisher\Monitoring\Exception\UnregisteredEntryException;

class Monitor implements MonitoringInterface
{
    
    private static $monitor;
    private $session;
    private $baseSessionKey;
    private $sessionKey;
    
    public static function getInstance(
            SessionInterface $session,
            string $sessionKey = 'Monitor',
            string $baseSessionKey = 'Publisher'
    ) {
        if (self::$monitor === null) {
            self::$monitor = new Monitor($session, $sessionKey, $baseSessionKey);
        }
        
        return self::$monitor;
    }
    
    private function __construct(
            SessionInterface $session,
            string $sessionKey,
            string $baseSessionKey
    ) {
        $this->session = $session;
        $this->baseSessionKey = $baseSessionKey;
        $this->sessionKey = $this->baseSessionKey.'/'.$sessionKey;
        
        $bag = new NamespacedAttributeBag();
        $this->session->registerBag($bag);
        
        if (!$this->issetStatus()) {
            $this->initStatus();
        }
    }

    /**
     * @{inheritdoc}
     */
    public function monitor(string $entryId)
    {
        if (!$this->issetEntry($entryId)) {
            $this->monitorEntry($entryId);
        }
    }
    
    /**
     * @{inheritdoc}
     */
    public function setStatus(string $entryId, bool $success = null)
    {
        $this->checkEntryExists($entryId);
        
        $this->setEntryStatus($entryId, $success);
    }
    
    /**
     * @{inheritdoc}
     */
    public function executed(string $entryId)
    {
        $this->checkEntryExists($entryId);
        
        return ($this->getEntryStatus($entryId) !== null);
    }
    
    /**
     * @{inheritdoc}
     */
    public function finished()
    {
        $finished = true;
        
        $status = $this->getStatus();
        foreach ($status as $entryId => $success) {
            $finished = $finished && ($success !== null);
        }
        
        return $finished;
    }
    
    /**
     * @{inheritdoc}
     */
    public function getStatus()
    {
        return $this->session->get($this->sessionKey);
    }
    
    /**
     * @{inheritdoc}
     */
    public function clearStatus()
    {
        $this->initStatus();
    }
    
    private function issetStatus()
    {
        return $this->session->has($this->sessionKey);
    }
    
    private function initStatus()
    {
        $this->session->set($this->sessionKey, array());
    }
    
    private function issetEntry(string $entryId)
    {
        return $this->session->has($this->sessionKey.'/'.$entryId);
    }
    
    private function monitorEntry(string $entryId)
    {
        $this->setEntryStatus($entryId, null);
    }
    
    private function checkEntryExists($entryId)
    {
        if (!$this->issetEntry($entryId)) {
            throw new UnregisteredEntryException("$entryId is not registered.");
        }
    }
    
    private function setEntryStatus(string $entryId, $status)
    {
        $this->session->set($this->sessionKey.'/'.$entryId, $status);
    }
    
    private function getEntryStatus(string $entryId)
    {
        return $this->session->get($this->sessionKey.'/'.$entryId);
    }
    
}