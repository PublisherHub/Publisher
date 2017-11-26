<?php

namespace Publisher\Selector\Manager;

use Publisher\Selector\Manager\SelectorManagerInterface;
use Publisher\Selector\Factory\SelectorFactoryInterface;
use Publisher\Selector\SelectorInterface;
use Publisher\Selector\Factory\SelectionCollectionArrayTransformerInterface;

class SelectorManager implements SelectorManagerInterface
{
    
    /**
     * @var SelectorInterface[]
     */
    protected $selectors;
    
    /**
     * @var SelectorFactoryInterface
     */
    protected $selectorFactory;
    
    /**
     * @var SelectionCollectionArrayTransformerInterface
     */
    protected $collectionTransformer;
    
        
    /**
     * @param SelectorFactoryInterface $selectorFactory
     * @param SelectionCollectionArrayTransformerInterface $collectionTransformer
     */
    public function __construct(
        SelectorFactoryInterface $selectorFactory,
        SelectionCollectionArrayTransformerInterface $collectionTransformer
    ) {
        $this->selectors = [];
        $this->selectorFactory = $selectorFactory;
        $this->collectionTransformer = $collectionTransformer;
    }
    
    /**
     * @inheritDoc
     */
    public function setupSelectors(array $entryIds, array $collectionsData = [])
    {
        $this->selectors = [];
        
        foreach ($entryIds as $entryId) {
            $selectionCollection = $this->collectionTransformer->getSelectionCollectionFromArray(
                isset($collectionsData[$entryId]) ? $collectionsData[$entryId] : []
            );
            $this->selectors[$entryId] = $this->selectorFactory->getSelector($entryId, $selectionCollection);
        }
    }
    
    /**
     * @inheritDoc
     */
    public function updateSelectors(array $decisions)
    {
        foreach ($this->selectors as $entryId => $selector) {
            
            if (isset($decisions[$entryId]) && is_array($decisions[$entryId])) {
                $selector->updateParameters($decisions[$entryId]);
            }
        }
    }
    
    /**
     * @inheritDoc
     */
    public function areAllParametersSet()
    {
        foreach ($this->selectors as $selector) {
            if ($selector->isParameterMissing()) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * @inheritDoc
     */
    public function executeCurrentSteps()
    {
        foreach ($this->selectors as $selector) {
            $selector->executeCurrentStep();
        }
    }
    
    /**
     * @inheritDoc
     */
    public function getCollections()
    {
        $selections = [];
        
        foreach ($this->selectors as $entryId => $selector) {
            $selections[$entryId] = $selector->getCollection();
        }
        
        return $selections;
    }
    
    /**
     * @inheritDoc
     */
    public function getCollectionsAsArray()
    {
        $selections = [];
        
        foreach ($this->selectors as $entryId => $selector) {
            $selections[$entryId] = $this->collectionTransformer->getSelectionCollectionAsArray($selector->getCollection());
        }
        
        return $selections;
    }
    
    /**
     * @inheritDoc
     */
    public function getParameters()
    {
        $parameters = [];
        
        foreach ($this->selectors as $entryId => $selector) {
            $parameters[$entryId] = $selector->getParameters();
        }
        
        return $parameters;
    }
    
}

