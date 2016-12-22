<?php

namespace Publisher\Manager;

use Publisher\Manager\PublishingManagerInterface;
use Publisher\Helper\EntryHelperInterface;
use Publisher\Entry\Factory\EntryFactoryInterface;
use Publisher\Requestor\RequestorFactoryInterface;
use Publisher\Monitoring\MonitoringInterface;
use Publisher\Entry\EntryInterface;
use Publisher\Requestor\RequestorInterface;

class Publisher implements PublishingManagerInterface
{
    /** @var \Publisher\Helper\EntryHelperInterface */
    protected $entryHelper;
    /** @var \Publisher\Entry\Factory\EntryFactoryInterface */
    protected $entryFactory;
    /** @var \Publisher\Requestor\RequestorFactoryInterface */
    protected $requestorFactory;
    /** @var \Publisher\Monitoring\Monitor */
    protected $monitor;
    /** @var array */
    protected $entries;
    
    public function __construct(
        EntryHelperInterface $entryHelper,
        EntryFactoryInterface $entryFactory,
        RequestorFactoryInterface $requestorFactory,
        MonitoringInterface $monitor
    ) {
        $this->entryHelper = $entryHelper;
        $this->entryFactory = $entryFactory;
        $this->requestorFactory = $requestorFactory;
        $this->monitor = $monitor;
    }
    
    /**
     * @{inheritdoc}
     */
    public function setupEntries(array $entryData)
    {
        $this->entries = array();
        $this->importEntries($entryData);
        
        if (empty($this->monitor->getStatus())) {
            $this->initialiseMonitoring();
        }
    }
    
    /**
     * @{inheritdoc}
     */
    public final function publishAll()
    {
        for ($i = 0; $i < count($this->entries); $i++) {
            
            $entryId = $this->entries[$i]['entry'];
            $parameters = $this->entries[$i]['parameters'];
            $content = $this->entries[$i]['content'];
            
            if (!$this->monitor->executed($entryId)) {
                $this->publishEntry($entryId, $parameters, $content);
            }
        }
    }
    
    /**
     * @{inheritdoc}
     */
    public function getStatus()
    {
        return $this->monitor->getStatus();
    }
    
    /**
     * @{inheritdoc}
     */
    public function hasFinished()
    {
        return $this->monitor->finished();
    }
    
    /**
     * @{inheritdoc}
     */
    public function clearStatus()
    {
        $this->monitor->clearStatus();
    }
    
    protected function importEntries(array $data)
    {
        for ($i = 0; $i < count($data); $i++) {
            $this->entryHelper->checkIsEntryId($data[$i]['entry']); 
            if (!isset($data[$i]['parameters'])) {
                $data[$i]['parameters'] = array();
            }
            $this->entries[] = array(
                'entry'   => $data[$i]['entry'],
                'content' => $data[$i]['content'],
                'parameters' => $data[$i]['parameters']
            );
        }
    }
    
    /**
     * Registers all entries at the monitor as 'not executed'.
     * 
     * @return void
     */
    protected function initialiseMonitoring()
    {
        for ($i = 0; $i < count($this->entries); $i++) {
            $this->monitor->monitor($this->entries[$i]['entry']);
        }
    }
    
    /**
     * Creates the entry, gets the request, executes it
     * and sets the status of the request as successful or failed.
     * 
     * @return void;
     */
    protected function publishEntry(
            string $entryId,
            array $parameters,
            array $content
    ) {
        $entry = $this->entryFactory->getEntry($entryId, $parameters);
        $entry->setBody($content);
        
        $response = $this->publish($entry);
            
        $this->setStatus($entryId, $entry, $response);
    }
    
    /**
     * Executes the request of the configured $entry.
     * 
     * @return mixed
     */
    protected function publish(EntryInterface $entry)
    {
        $requestor = $this->getRequestor($entry);
        
        return $requestor->doRequest($entry->getRequest());
    }
    
    /**
     * Returns a RequestorInterface based on $entry.
     * 
     * @param EntryInterface $entry
     * 
     * @return RequestorInterface
     */
    protected function getRequestor(EntryInterface $entry)
    {
        return $this->requestorFactory->createByEntry($entry);
    }
    
    /**
     * Sets the status of the request marked as $entryId as executed
     * and based on $response as successful or failed.
     * 
     * @param string $entryId
     * @param EntryInterface $entry
     * @param mixed $response
     * 
     * @return void
     */
    protected function setStatus(
            string $entryId,
            EntryInterface $entry,
            $response
    ) {
        if ($response === false) {
            $this->monitor->setStatus($entryId, false);
        } else {
            $this->monitor->setStatus($entryId, $entry::succeeded($response));
        }
    }
    
}