<?php

namespace Publisher\Selector;

use Publisher\Selector\SelectorInterface;
use Publisher\Selector\Selection\SelectionCollection;

/**
 * A default selector that may be used for entries that don't require any Selectors.
 */
class NullSelector implements SelectorInterface
{
    
    /**
     * {@inheritDoc}
     */
    public function getCollection()
    {
        return new SelectionCollection();
    }
    
    /**
     * {@inheritDoc}
     */
    public function updateParameters(array $decisions)
    {
        // do nothing
    }
    
    /**
     * {@inheritDoc}
     */
    public function isParameterMissing()
    {
        return false;
    }
    
    /**
     * {@inheritDoc}
     */
    public function executeCurrentStep()
    {
        // do nothing
    }
    
    /**
     * {@inheritDoc}
     */
    public function getParameters()
    {
        return [];
    }
    
}
