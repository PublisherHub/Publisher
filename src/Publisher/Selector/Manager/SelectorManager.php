<?php

namespace Publisher\Selector\Manager;

use Publisher\Selector\Manager\SelectorManagerInterface;
use Publisher\Selector\Factory\SelectorFactoryInterface;
use Publisher\Selector\SelectorInterface;
use Publisher\Selector\Selection;

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
     * @param SelectorFactoryInterface $selectorFactory
     */
    public function __construct(SelectorFactoryInterface $selectorFactory)
    {
        $this->selectorFactory = $selectorFactory;
    }
    
    /**
     * @{inheritdoc}
     */
    public function setupSelectors(array $entryIds)
    {
        $this->selectors = array();
        
        foreach ($entryIds as $entryId) {
            $this->selectors[$entryId] = $this->selectorFactory->getSelector($entryId);
        }
    }
    
    /**
     * @{inheritdoc}
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
     * @{inheritdoc}
     */
    public function updateSelectors(array $decisions)
    {
        $entryIds = array_keys($this->selectors);
        foreach ($this->selectors as $entryId => $selector) {
            if (isset($decisions[$entryId]) && is_array($decisions[$entryId])) {
                $updateData = $decisions[$entryId];
            } else {
                $updateData = array();
            }
            $selector->updateParameters($updateData);
        }
    }
    
    /**
     * @{inheritdoc}
     */
    public function getSelections()
    {
        $selections = array();
        
        foreach ($this->selectors as $entryId => $selector) {
            $selections[$entryId] = $selector->getSelections();
        }
        
        return $selections;
    }
    
    /**
     * @{inheritdoc}
     */
    public function getSelectionsAsArray() // @todo move outside this class
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
        
        foreach ($this->selectors as $entryId => $selector) {
            $parameters[$entryId] = $selector->getParameters();
        }
        
        return $parameters;
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

