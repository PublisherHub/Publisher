<?php

namespace Publisher\Manager;

use Publisher\Manager\PublishingManagerInterface;
use Publisher\Requestor\RequestorFactoryInterface;
use Publisher\Monitoring\Monitor;
use Publisher\Entry\EntryInterface;
use Publisher\Helper\EntryHelperInterface;
use Publisher\Entry\Factory\EntryFactoryInterface;

abstract class AbstractPublishingManager implements PublishingManagerInterface
{
    protected $entryHelper;
    /** @var array */
    protected $entries;
    /** @var \Publisher\Entry\Factory\EntryFactoryInterface */
    protected $entryFactory;
    /** @var \Publisher\Requestor\RequestorFactoryInterface */
    protected $requestorFactory;
    /** @var \Publisher\Monitoring\Monitor */
    protected $monitor;
    
    public function __construct(
            EntryHelperInterface $entryHelper,
            array $entryData,
            EntryFactoryInterface $entryFactory,
            RequestorFactoryInterface $requestorFactory,
            Monitor $monitor
    ) {
        $this->entryHelper = $entryHelper;
        $this->entries = array();
        $this->importEntries($entryData);
        
        $this->entryFactory = $entryFactory;
        $this->requestorFactory = $requestorFactory;
        
        $this->monitor = $monitor;
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
                $entry = $this->entryFactory->getEntry($entryId, $parameters);
                $this->fillEntry($entry, $content);
                $response = $this->publish($entry);
                $this->monitor->setStatus($entryId, $entry::succeeded($response));
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
    public function clearStatus()
    {
        if ($this->monitor->finished()) {
            $this->monitor->clearStatus();
        }
    }
    
    
    protected function importEntries(array $data)
    {
        for ($i = 0; $i < count($data); $i++) {
            $this->entryHelper->checkIsEntryId($data[$i]['entry']);
            $this->validateContent($data[$i]['content']); 
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
     * @return void;
     */
    protected abstract function validateContent(array $content);
    
    protected abstract function fillEntry(EntryInterface $entry, array $content);
    /**
     * @return string
     */
    protected abstract function publish(EntryInterface $entry);
    
    protected function getRequestor(EntryInterface $entry)
    {
        return $this->requestorFactory->createByEntry($entry);
    }
    
    protected function initialiseMonitoring()
    {
        for ($i = 0; $i < count($this->entries); $i++) {
            $this->monitor->monitor($this->entries[$i]['entry']);
        }
        
    }
}