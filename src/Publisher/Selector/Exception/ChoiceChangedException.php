<?php

namespace Publisher\Selector\Exception;

class ChoiceChangedException extends \Exception
{
    
    protected $stepId;
    
    public function setStepId(int $stepId)
    {
        $this->stepId = $stepId;
    }
    
    public function getStepId()
    {
        return $this->stepId;
    }
}