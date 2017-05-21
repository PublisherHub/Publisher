<?php

namespace Publisher\Entry\Service\Selector;

use Publisher\Selector\Selection\SelectorDefinition;
use Publisher\Selector\Selection\SelectionCollectionInterface;
use Publisher\Requestor\Request;

class ServicePageSelectorDefinition extends SelectorDefinition
{

    protected function defineSteps()
    {
        $this->steps[0] = function () {
            return new Request();
        };
        $this->steps[1] = function () {
            return new Request();
        };
    }
    
    protected function defineDecisionOrder()
    {
        $this->decisionOrder = [];
    }
    
    public function getRequiredParameters(array $decisions)
    {
        return [];
    }

    public function isDecisionMissing()
    {
        return false;
    }

    public function updateDecisions(SelectionCollectionInterface $selectionCollection, string $response)
    {
        
    }

}

