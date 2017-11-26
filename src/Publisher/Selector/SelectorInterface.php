<?php

namespace Publisher\Selector;

use Publisher\Selector\Selection\SelectionCollectionInterface;

/**
 * This interface is used to require parameters through a stepwise execution
 * of chronological ordered requests.
 * 
 * You may firstly update the Parameters,
 * then determine if a parameter is missing.
 * 
 * If required parameters are missing
 * you should execute the current step to gain another selection.
 * You can get those selections from an SelectionCollection instance.
 * 
 * If no parameter is missing you finally can get all required parameters.
 */
interface SelectorInterface
{
    
    /**
     * Returns an instance of the SelectionCollectionInterface
     * that contains the decisions and selections that were made until now.
     * 
     * @return SelectionCollectionInterface;
     */
    public function getCollection();
    
    /**
     * Updates the decisions and selections based on $decisions.
     * 
     * @return void
     */
    public function updateParameters(array $decisions);
    
    /**
     * Returns true if required parameters are missing
     * and false otherwise.
     * 
     * @return bool
     */
    public function isParameterMissing();
    
    /**
     * Executes the next request based on the decisions and selections
     * that were made previously and updates them based on the response.
     * 
     * @return void
     */
    public function executeCurrentStep();
    
    /**
     * Returns the required Parameters.
     * 
     * @return array associative array e.g ['param1' => 'decision1', ...]
     */
    public function getParameters();
    
}
