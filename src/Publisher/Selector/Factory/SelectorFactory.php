<?php

namespace Publisher\Selector\Factory;

use Publisher\Selector\Factory\SelectorFactoryInterface;

use Publisher\Requestor\RequestorFactoryInterface;
use Publisher\Storage\StorageInterface;

use Publisher\Selector\Exception\SelectorNotFoundException;
use Publisher\Helper\EntryHelperInterface;
use Publisher\Selector\Parameter\NullSelector;

class SelectorFactory implements SelectorFactoryInterface
{
    
    protected $entryHelper;
    protected $requestorFactory;
    protected $storage;
    
    public function __construct(
            EntryHelperInterface $entryHelper,
            RequestorFactoryInterface $requestorFactory,
            StorageInterface $storage
    ) {
        $this->entryHelper = $entryHelper;
        $this->requestorFactory = $requestorFactory;
        $this->storage = $storage;
    }
    
    /**
     * @{inheritData}
     */
    public function create(string $entryId, array $additionalScopes = array())
    {
        try {
            $class = $this->entryHelper->getSelectorClass($entryId);
            
            $serviceId = $this->entryHelper->getServiceId($entryId);
            $scopes = array_merge(
                    $additionalScopes,
                    $this->entryHelper->getPublisherScopes($entryId)
            );
            $requestor = $this->requestorFactory->create(
                    $serviceId,
                    $scopes
            );
            
            return new $class($requestor, $this->storage);
            
        } catch (SelectorNotFoundException $ex) {
            $this->entryHelper->checkIsEntryId($entryId);
            
            return $this->getDefaultParameterSelector();
        }
    }
    
    protected function getDefaultParameterSelector()
    {
        return new NullSelector();
    }
}
