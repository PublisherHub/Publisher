<?php

namespace Publisher\Selector;

use Publisher\Selector\SelectorInterface;
use Publisher\Requestor\RequestorInterface;
use Publisher\Selector\Selection\SelectorDefinitionInterface;
use Publisher\Selector\Selection\SelectionCollectionInterface;

class Selector implements SelectorInterface
{
    
    /**
     * @var SelectionCollectionInterface
     */
    protected $selectionCollection;
    
    /**
     * @var Requestor
     */
    protected $requestor;
    
    /**
     * @var SelectionDefintionInterface
     */
    protected $selectorDefinition;
    
    /**
     * @param RequestorInterface $requestor
     * @param SelectorDefinitionInterface $selectorDefinition
     * @param SelectionCollectionInterface $selectionCollection
     */
    public function __construct(
        RequestorInterface $requestor,
        SelectorDefinitionInterface $selectorDefinition,
        SelectionCollectionInterface $selectionCollection
    ) {
        $this->requestor = $requestor;
        $this->selectorDefinition = $selectorDefinition;
        $this->selectionCollection = $selectionCollection;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCollection()
    {
        return $this->selectionCollection;
    }
    
    /**
     * {@inheritDoc}
     */
    public function updateParameters(array $decisions)
    {
        $decisionOrder = $this->selectorDefinition->getDecisionOrder();
        
        $numberOfDecisions = count($decisionOrder);
        for ($i = 0; $i < $numberOfDecisions; $i++) {
            $paramId = $decisionOrder[$i];
            if (isset($decisions[$paramId])) {
                $this->selectionCollection->makeDecision(
                    $paramId,
                    $decisions[$paramId]
                );
            }
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function isParameterMissing()
    {
        return $this->selectorDefinition->isDecisionMissing(
            $this->selectionCollection->getDecisions()
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function executeCurrentStep()
    {
        $request = $this->selectorDefinition->getRequest(
            $this->selectionCollection
        );
        $response = $this->requestor->doRequest($request);
        $this->selectorDefinition->updateDecisions($this->selectionCollection, $response);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getParameters()
    {
        return $this->selectorDefinition->getRequiredParameters(
            $this->selectionCollection->getDecisions()
        );
    }
    
}
