<?php

namespace Publisher\Selector\Selection;

interface SelectionCollectionInterface
{
    
    /**
     * Returns an associative array.
     * 
     * Example: ['parameter1' => 'decision1', 'parameter2' => 'decision2', ...]
     * 
     * @return array
     */
    public function getDecisions();
    
    /**
     * Saves the decision made for an parameter with the id $key.
     * 
     * Resets all following decisions and selections
     * if a decision was saved before AND but changed
     * for the parameter with the id $key.
     * 
     * @param string $paramId  an id that refers to a parameter
     * @param string $decision 
     * 
     * @return void
     */
    public function makeDecision(string $paramId, string $decision);
    
    /**
     * @param string $paramId an id that refers to a parameter
     * 
     * @return bool
     */
    public function hasDecided(string $paramId);
    
    /**
     * Returns an array of Selection instances
     * in their chronological order.
     * 
     * @return Selection[]
     */
    public function getSelections();
    
    /**
     * Adds an Selection instance as the currently chronological last element.
     * 
     * @param string   $paramId an id that rwefers to a parameter
     * @param string[] $options
     * 
     * @return void
     */
    public function addSelection(string $key, array $options);
    
    /**
     * @return int
     */
    public function getCurrentStepId();
}
