<?php

namespace Publisher\Selector\Selection;

use Publisher\Selector\Selection;
use Publisher\Selector\Selection\SelectionCollectionInterface;

class SelectionCollection implements SelectionCollectionInterface
{
    
    /**
     * @var array 
     */
    protected $decisions;
    
    /**
     * @var Selection[]
     */
    protected $selections;
    
    /**
     * @var int
     */
    protected $stepId;
    
    /**
     * @param array       $decisions
     * @param Selection[] $selections
     */
    public function __construct(array $decisions = [], array $selections = [])
    {
        $this->decisions = $decisions;
        $this->selections = $selections;
        $this->stepId = count($this->decisions);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDecisions()
    {
        return $this->decisions;
    }
    
    /**
     * {@inheritDoc}
     */
    public function makeDecision(string $paramId, string $decision)
    {
        if ($this->hasDecided($paramId) && $this->changedHisMind($paramId, $decision)) {
            $this->resetFollowingDecisions($this->getStepIdOfParamId($paramId));
        }
        $this->decisions[$paramId] = $decision;
        $this->markNextStep();
    }
    
    /**
     * {@inheritDoc}
     */
    public function hasDecided(string $paramId)
    {
        return isset($this->decisions[$paramId]);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getSelections()
    {
        return $this->selections;
    }
    
    /**
     * {@inheritDoc}
     */
    public function addSelection(string $key, array $options)
    {
        $this->selections[] = new Selection($key, $options);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCurrentStepId()
    {
        return $this->stepId;
    }
    
    /**
     * @param string $key
     * @param string $decision
     * 
     * @return bool
     */
    protected function changedHisMind(string $key, string $decision)
    {
        return $this->decisions[$key] !== $decision;
    }
    
    
    protected function resetFollowingDecisions(int $stepId)
    {
        if ($stepId !== false) {
            for ($i = count($this->selections) - 1; $i > $stepId; $i--) {
                $selectionName = $this->selections[$i]->getName();
                unset($this->selections[$i]);
                unset($this->decisions[$selectionName]);
            }
            $this->stepId = $stepId;
        }
    }
    
    /**
     * Returns the stepId that belongs to a paramId
     * or false in case no matching stepId was found.
     * 
     * @param string $paramId an id that refers to a parameter
     * 
     * @return int|false
     */
    protected function getStepIdOfParamId(string $paramId)
    {
        $stepId = false;
        
        for ($i = 0; $i < count($this->selections); $i++) {
            if ($this->selections[$i]->getName() === $paramId) {
                $stepId = $i;
                continue;
            }
        }
        
        return $stepId;
    }
    
    protected function markNextStep()
    {
        $this->stepId++;
    }
    
}
