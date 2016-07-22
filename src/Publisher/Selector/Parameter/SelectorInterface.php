<?php

namespace Publisher\Selector\Parameter;

interface SelectorInterface
{
    
    /**
     * Updates the results based on $choices.
     * If a choice respectively result changes in the process,
     * then all following choices and selections will be deleted.
     * 
     * @param array $choices
     * 
     * @return void
     */
    public function updateParameters(array $choices);
    
    /**
     * Returns an array of instances of Publisher\Selector\Selection,
     * that should be used to obtain the required parameter for the Entry.
     * 
     * @return array
     */
    public function getSelections();
    
    /**
     * Returns whether or not the required parameters are missing.
     * 
     * @return bool
     */
    public function isParameterMissing();
    
    /**
     * Returns only the parameters that the related Entry requires.
     * 
     * @return array
     */
    public function getParameters();
    
}
