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
     * @var RequestorFactoryInterface
     */
    protected $requestorFactory;
    
    /**
     * Holds additional scopes per Entry (optional).
     * 
     * Example:
     * $additionalScopes = [
     *     'ServiceUser' => ['scope1', 'scope2', ...]
     * ];
     * 
     * @var string array
     */
    protected $additionalScopes;
    
    /**
     * @param EntryHelperInterface $entryHelper
     * @param RequestorFactoryInterface $requestorFactory
     * @param string[]                  $additionalScopes
     */
    public function __construct(
        EntryHelperInterface $entryHelper,
        RequestorFactoryInterface $requestorFactory,
        array $additionalScopes = []
    ) {
        $this->entryHelper = $entryHelper;
        $this->requestorFactory = $requestorFactory;
        $this->additionalScopes = $additionalScopes;
    }
    
    /**
     * @{inheritData}
     */
    public function getSelector(
        string $entryId,
        SelectionCollectionInterface $selectionCollection = null
    ) {
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
        
        $requestor = $this->requestorFactory->create(
            $this->entryHelper->getServiceId($entryId),
            $this->getScopes($entryId)
        );
        
        if (!$selectionCollection) {
            $selectionCollection = $this->getDefaultSelectionCollection();
        }
        
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
     * @return NullSelector
     */
    protected function getDefaultSelector()
    {
        return new NullSelector();
    }
    
    /**
     * @param string $entryId
     */
    public function getScopes(string $entryId)
    {
        if (isset($this->additionalScopes[$entryId])) {
            return array_merge(
                $this->additionalScopes[$entryId],
                $this->entryHelper->getPublisherScopes($entryId)
            );
        }
        
        return $this->entryHelper->getPublisherScopes($entryId);
    }
    
    /**
     * @return SelectionCollectionInterface
     */
    protected function getDefaultSelectionCollection()
    {
        return new SelectionCollection();
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
