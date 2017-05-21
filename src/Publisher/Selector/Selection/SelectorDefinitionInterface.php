<?php

namespace Publisher\Selector\Selection;

use Publisher\Requestor\Request;
use Publisher\Selector\Selection\SelectionCollectionInterface;

interface SelectorDefinitionInterface
{
    
    /**
     * Returns the chronological order of the decisions
     * that need to be made.
     * 
     * Sugesstion: use it for e.g. a progress bar.
     * 
     * @return string[]
     */
    public function getDecisionOrder();
    
    /**
     * Returns whether or not the required parameters are missing.
     * 
     * @return bool
     */
    public function isDecisionMissing();
    
    /**
     * Returns the Request that is definded for the step refered by
     * the current step id of $selectionCollection.
     * 
     * If there is no Request defined for the current step id,
     * null will be returned.
     * The previous made decisions - saved in $selectionCollection -
     * may be used to generate the Request instances.
     * 
     * @param SelectionCollectionInterface $selectionCollection
     * 
     * @return Request
     */
    public function getRequest(SelectionCollectionInterface $selectionCollection);
    
    /**
     * Updates the decisions and selections of $selectionCollection,
     * based on the given response.
     * 
     * @param SelectionCollectionInterface $selectionCollection
     * @param string $response
     * 
     * @return void
     */
    public function updateDecisions(
        SelectionCollectionInterface $selectionCollection,
        string $response
    );
    
    /**
     * Returns only the parameters that the related Entry requires.
     * 
     * @param string[] $decisions
     * 
     * @return array
     */
    public function getRequiredParameters(array $decisions);
    
}
