<?php

namespace Publisher\Entry\Service\Selector;

use Publisher\Selector\Selection\SelectorDefinition;
use Publisher\Selector\Selection\SelectionCollectionInterface;
use Publisher\Requestor\Request;

class ServicePageSelectorDefinition extends SelectorDefinition
{

    /**
     * @inheritDoc
     */
    protected function defineSteps()
    {
        $this->steps[0] = function () {
            return new Request();
        };
        $this->steps[1] = function () {
            return new Request();
        };
    }
    
    /**
     * @inheritDoc
     */
    protected function defineDecisionOrder()
    {
        $this->decisionOrder = [];
    }
    
    /**
     * @inheritDoc
     */
    public function getRequiredParameters(array $decisions)
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function isDecisionMissing(array $decisions)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function updateDecisions(SelectionCollectionInterface $selectionCollection, string $response)
    {
        
    }

}

