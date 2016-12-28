<?php

namespace Publisher\Selector\Parameter;

use Publisher\Selector\Parameter\SelectorInterface;
use Publisher\Requestor\RequestorInterface;
use Publisher\Storage\StorageInterface;

use Publisher\Selector\Exception\ChoiceChangedException;
use Publisher\Selector\Exception\MissingResponseException;

abstract class AbstractSelector implements SelectorInterface
{
    
    protected $requestor;
    protected $storage;
    protected $steps;
    protected $selections;
    protected $results;
    
    protected $requestorKey;
    protected $resultsKey;
    protected $selectionsKey;
    
    public function __construct(
            RequestorInterface $requestor,
            StorageInterface $storage
    ) {
        $this->requestor = $requestor;
        $this->storage = $storage;
        $this->steps = array();
        $this->defineSteps();
        
        $this->setStorageKeys();
        
        $results = $this->getResultsFromStorage();
        $this->results = is_null($results) ? array() : $results;
        
        $selections = $this->getSelectionsFromStorage();
        $this->selections = is_null($selections) ? array() : $selections;
    }
    
    /**
     * @{inheritdoc}
     */
    public final function updateParameters(array $choices)
    {
        try {
            $this->matchParameter($choices);
            $this->updateResults();
        } catch (ChoiceChangedException $ex) {
            $this->refreshSelectionsAfter($ex->getStepId());
        }
        if ($this->isParameterMissing()) {
            $this->doRequest();
        }
    }
    
    /**
     * @{inheritdoc}
     */
    public final function getSelections()
    {
        return $this->selections;
    }
    
    /**
     * @{inheritdoc}
     */
    abstract public function isParameterMissing();
    
    /**
     * @{inheritdoc}
     */
    abstract public function getParameters();
    
    
    /**
     * Defines the required steps to obtain the required parameters
     * by adding Request objects to $this->steps in chronological order.
     * 
     * @return void
     */
    abstract protected function defineSteps();
    
    /**
     * Matches the given choices with the expected results.
     * 
     * @return void
     */
    abstract protected function matchParameter(array $choices);
    
    /**
     * Create and store a result or selection based on the response
     * returned by $this->doRequest().
     * 
     * @return void
     */
    abstract protected function saveResult(int $stepId, string $response);
    
    
    protected function setStorageKeys()
    {
        $this->requestorKey = $this->getSelectorKey();
        $this->resultsKey = 'results';
        $this->selectionsKey = 'selections';
    }
    
    protected function getSelectorKey()
    {
        $classname = get_class($this);
        return preg_replace('/^.*\\\\([A-Za-z]+)Selector/', "$1", $classname);
    }
    
    protected function getResultsFromStorage()
    {
        return $this->storage->get($this->requestorKey, $this->resultsKey);
    }
    
    protected function getSelectionsFromStorage()
    {
        return $this->storage->get($this->requestorKey, $this->selectionsKey);
    }
    
    protected final function setResult(
            int $stepId,
            string $parameterId,
            string $value
    ) {
        if (!isset($this->results[$parameterId])) {
            $this->results[$parameterId] = $value;
            
        } elseif ($this->results[$parameterId] !== $value) {
            $this->results[$parameterId] = $value;
            $this->handleChanges($stepId);
        }
    }
    
    protected function handleChanges(int $stepId)
    {
        $exception = new ChoiceChangedException('Choice changed.');
        $exception->setStepId($stepId);
        throw $exception;
    }
    
    /**
     * Remove all choices (results) and selections after step $stepId.
     * 
     * @param int $stepId
     * 
     * @return void
     */
    protected final function refreshSelectionsAfter(int $stepId)
    {
        for ($i = $stepId+1; $i < count($this->selections); $i++) {
            $selectionId = $this->selections[$i]->getName();
            unset($this->selections[$i]);
            unset($this->results[$selectionId]);
        }
        $this->updateSelections();
        $this->updateResults();
    }
    
    protected function updateSelections()
    {
        $this->storage->set(
                    $this->requestorKey,
                    $this->selectionsKey,
                    $this->selections
        );
    }
    
    protected function updateResults()
    {
        $this->storage->set(
                $this->requestorKey,
                $this->resultsKey,
                $this->results
        );
    }
    
    /**
     * Request and store the choices for the current step.
     * 
     * @return void
     */
    protected final function doRequest()
    {
        $stepId = count($this->results);
        $request = $this->steps[$stepId]($this->results);
        $response = $this->requestor->doRequest($request);
        $this->checkGotResponse($response);
        $this->saveResult($stepId, $response);
    }
    
    protected function checkGotResponse($response)
    {
        if (is_null($response)) {
            throw new MissingResponseException("Request failed.");
        }
    }
}