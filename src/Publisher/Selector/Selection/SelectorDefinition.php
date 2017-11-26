<?php

namespace Publisher\Selector\Selection;

use Publisher\Selector\Selection\SelectorDefinitionInterface;
use Publisher\Selector\Selection\SelectionCollectionInterface;
use Publisher\Selector\Selection;

/**
 * A SelectorDefinition defines which steps or Request are necessary
 * to retrieve certain parameters that an Entry requires to be published.
 * 
 * A PageEntry may require a pageId and an additional access token.
 * To retrieve those parameters multiple requests may be necessary.
 * Those steps are defined chronological in the a specified SelectorDefinition.
 * 
 * A SelectorDefinition implements methods to ascertain missing parameters
 * that are required by an Entry, to return only the required parameters,
 * and methods to get the necessary Requests.
 */
abstract class SelectorDefinition implements SelectorDefinitionInterface
{
    
    /**
     * @var Selection[]
     */
    protected $steps;
    
    /**
     * @var string[] 
     */
    protected $decisionOrder;
    
    
    public function __construct()
    {
        $this->defineSteps();
        $this->defineDecisionOrder();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRequest(SelectionCollectionInterface $selectionCollection)
    {
        $stepId = $selectionCollection->getCurrentStepId();
        
        if (isset($this->steps[$stepId])) {
            return $this->steps[$stepId]($selectionCollection->getDecisions());
        }
        
        return null;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDecisionOrder()
    {
        return $this->decisionOrder;
    }
    
    /**
     * Define the steps as callbacks that create Requests
     * based on the current decisions.
     * 
     * Example:
     *     $this->steps[0] = function (array $decisions) {
     *         return new Request('/me/accounts', 'GET');
     *     };
     *     $this->steps[1] = function (array $decisions) {
     *         return new Request('/' . $decisions['pageId'] . '?fields=access_token', 'GET');
     *     };
     * 
     * @return void
     */
    abstract protected function defineSteps();
    
    /**
     * Sets the value for $this->decisionOrder.
     * 
     * @return string[]
     */
    abstract protected function defineDecisionOrder();
    
}
