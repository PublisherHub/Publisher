<?php

namespace Publisher;

use Publisher\Interfaces\PublisherFactoryInterface;
use Publisher\Monitoring\Monitor;
use Publisher\Entry\EntryInterface;
use Publisher\Helper\EntryHelper;
use Publisher\Factory\EntryFactory;

abstract class AbstractPublishingManager
{
    /** @var array */
    protected $entries;
    /** @var \Publisher\Factory\PublisherFactoryInterface */
    protected $publisherFactory;
    /** @var \Publisher\Monitoring\Monitor */
    protected $monitor;
    
    public function __construct(
            array $entryData,
            PublisherFactoryInterface $publisherFactory,
            Monitor $monitor
    ) {
        $this->entries = array();
        $this->importEntries($entryData);
        $this->publisherFactory = $publisherFactory;
        $this->monitor = $monitor;
        if (empty($this->monitor->getStatus())) {
            $this->initialiseMonitoring();
        }
    }
    
    public function publishAll()
    {
        foreach ($this->entries as $serviceEntryName => $content) {
            if (!$this->monitor->executed($serviceEntryName)) {
                $entry = EntryFactory::getEntry($serviceEntryName, $content);
                $this->fillEntry($entry, $content);
                $status = $this->publish($entry);
                $this->monitor->setStatus($serviceEntryName, $entry::succeeded($status));
            }
        }
        if ($this->monitor->finished()) {
            
            $this->monitor->clearStatus();
        }
    }
    
    public function getStatus()
    {
        return $this->monitor->getStatus();
    }
    
    protected function importEntries(array $data)
    {
        foreach ($data as $entryName => $content) {
            if (EntryHelper::isEntryName($entryName)) {
                $this->validateContent($content);
                $this->entries[$entryName] = $content;
            }
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
    
    protected function getPublisher(EntryInterface $entry)
    {
        return $this->publisherFactory->getPublisherByEntry($entry);
    }
    
    protected function initialiseMonitoring()
    {
        foreach ($this->entries as $entryName => $content)
        {
            $this->monitor->monitor($entryName);
        }
    }
}