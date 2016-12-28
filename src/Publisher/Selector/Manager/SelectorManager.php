<?php

namespace Publisher\Selector\Manager;

use Publisher\Selector\Manager\SelectorManagerInterface;
use Publisher\Selector\Factory\SelectorFactoryInterface;
use Publisher\Selector\Selection;

class SelectorManager implements SelectorManagerInterface
{
    
    protected $selectors;
    protected $selectorFactory;
    
    public function __construct(SelectorFactoryInterface $selectorFactory)
    {
        $this->selectorFactory = $selectorFactory;
    }
    
    /**
     * @{inheritdoc}
     */
    public function setupSelectors(array $entryIds)
    {
        $this->createSelectors($entryIds);
    }
    
    /**
     * @{inheritdoc}
     */
    public function areAllParametersSet()
    {
        $selectors = array_keys($this->selectors);
        
        foreach ($selectors as $entryId) {
            if ($this->selectors[$entryId]->isParameterMissing()) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * @{inheritdoc}
     */
    public function updateSelectors(array $choices)
    {
        $selectors = array_keys($this->selectors);
        foreach ($selectors as $entryId) {
            if (isset($choices[$entryId]) && is_array($choices[$entryId])) {
                $updateData = $choices[$entryId];
            } else {
                $updateData = array();
            }
            $this->selectors[$entryId]->updateParameters($updateData);
            
        }
    }
    
    /**
     * @{inheritdoc}
     */
    public function getSelections()
    {
        $selections = array();
        
        $selectors = array_keys($this->selectors);
        foreach ($selectors as $entryId) {
            $selections[$entryId] = $this->selectors[$entryId]->getSelections();
        }
        
        return $selections;
    }
    
    /**
     * @{inheritdoc}
     */
    public function getSelectionsAsArray()
    {
        $selections = $this->getSelections();
        $return = array();
        foreach ($selections as $entryId => $selections) {
            $return[$entryId] = $this->convertSelectionsToArray($selections);
        }
        
        return $return;
    }
    
    /**
     * @{inheritdoc}
     */
    public function getParameters()
    {
        $parameters = array();
        
        $selectors = array_keys($this->selectors);
        foreach ($selectors as $entryId) {
            $parameters[$entryId] = $this->selectors[$entryId]->getParameters();
        }
        
        return $parameters;
    }
    
    protected function createSelectors(array $entryIds)
    {
        $this->selectors = array();
        foreach ($entryIds as $entryId) {
            $this->selectors[$entryId] = $this->selectorFactory->create($entryId);
        }
    }
    
    protected function convertSelectionsToArray(array $selections)
    {
        $return = array();
        
        for ($i = 0; $i < count($selections); $i++) {
            $return[$i] = $this-> convertSelectionToArray($selections[$i]);
        }
        
        return $return;
    }
    
    protected function convertSelectionToArray(Selection $selection)
    {
        return array(
            'name' => $selection->getName(),
            'choices' => $selection->getChoices()
        );
    }
    
}

