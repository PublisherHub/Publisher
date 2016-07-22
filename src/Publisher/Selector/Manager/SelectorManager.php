<?php

namespace Publisher\Selector\Manager;

use Publisher\Selector\Factory\SelectorFactoryInterface;
use Publisher\Selector\Selection;

class SelectorManager
{
    
    protected $selectors;
    protected $selectorFactory;
    
    public function __construct(
            array $entryIds,
            SelectorFactoryInterface $selectorFactory
    ) {
        $this->selectorFactory = $selectorFactory;
        $this->createSelectors($entryIds);
    }
    
    public function updateSelectors(array $choices)
    {
        $selectors = array_keys($this->selectors);
        foreach ($selectors as $entryId) {
            if (isset($choices[$entryId]) && is_array($choices[$entryId])) {
                $this->selectors[$entryId]->updateParameters($choices[$entryId]);
            }
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
    
    protected function createSelectors(array $entryIds)
    {
        $this->selectors = array();
        foreach ($entryIds as $entryId) {
            $this->selectors[$entryId] = $this->selectorFactory->create($entryId);
        }
    }
    
}

