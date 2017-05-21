<?php

namespace Publisher\Selector\Factory;

use Publisher\Selector\Factory\SelectorFactoryInterface;

use Publisher\Requestor\RequestorFactoryInterface;
use Publisher\Requestor\RequestorInterface;
use Publisher\Selector\Exception\SelectorDefinitionNotFoundException;
use Publisher\Helper\EntryHelperInterface;
use Publisher\Selector\NullSelector;
use Publisher\Selector\SelectorInterface;
use Publisher\Selector\Selector;
use Publisher\Selector\Selection\SelectorDefinitionInterface;
use Publisher\Selector\Selection\SelectionCollectionInterface;
use Publisher\Selector\Selection\SelectionCollection;

class SelectorFactory implements SelectorFactoryInterface
{
    
    /**
     * @var EntryHelperInterface
     */
    protected $entryHelper;
    
    /**
     *
     * @var RequestorFactoryInterface
     */
    protected $requestorFactory;
    
    /**
     * @param EntryHelperInterface $entryHelper
     * @param RequestorFactoryInterface $requestorFactory
     */
    public function __construct(
            EntryHelperInterface $entryHelper,
            RequestorFactoryInterface $requestorFactory
    ) {
        $this->entryHelper = $entryHelper;
        $this->requestorFactory = $requestorFactory;
    }
    
    /**
     * @{inheritData}
     */
    public function create(string $entryId, array $additionalScopes = array())
    {
        try {
            $selectorDefinition = $this->getSelectorDefinition($entryId);
            
        } catch (SelectorDefinitionNotFoundException $ex) {
            // It only makes sense to continue if the entry id is valid
            $this->entryHelper->checkIsEntryId($entryId);
            /* If no SelectorDefinition is defined
             * then then Entry doesn't require any Selector
             */
            return $this->getDefaultSelector();
        }
        
        $serviceId = $this->entryHelper->getServiceId($entryId);
        $scopes = array_merge(
            $additionalScopes,
            $this->entryHelper->getPublisherScopes($entryId)
        );
        $requestor = $this->requestorFactory->create(
            $serviceId,
            $scopes
        );
        
        $selectionCollection = $this->getSelectionCollection();
        
        return $this->createSelector(
            $requestor,
            $selectorDefinition,
            $selectionCollection
        );
    }
    
    /**
     * @param string $entryId
     * 
     * @return SelectorDefinitionInterface
     */
    protected function getSelectorDefinition(string $entryId)
    {
        $class = $this->entryHelper->getSelectorDefinitionClass($entryId);
        
        return new $class();
    }
    
    /**
     * @return SelectionCollectionInterface
     */
    protected function getSelectionCollection()
    {
        return new SelectionCollection();
    }
    
    /**
     * @return NullSelector
     */
    protected function getDefaultSelector()
    {
        return new NullSelector();
    }
    
    /**
     * @param RequestorInterface $requestor
     * @param SelectorDefinitionInterface $selectorDefinition
     * @param SelectionCollectionInterface $selectionCollection
     * 
     * @return SelectorInterface
     */
    protected function createSelector(
        RequestorInterface $requestor,
        SelectorDefinitionInterface $selectorDefinition,
        SelectionCollectionInterface $selectionCollection
    ) {
        return new Selector($requestor, $selectorDefinition, $selectionCollection);
    }
    
}
