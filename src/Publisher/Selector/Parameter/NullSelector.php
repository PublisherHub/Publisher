<?php

namespace Publisher\Selector\Parameter;

use Publisher\Selector\Parameter\SelectorInterface;

/**
 * A ParameterSelector for all classes that doesn't need any.
 * Should only be used if an automatic procedure requires it.
 */
class NullSelector implements SelectorInterface
{
    
    /**
     * @{inheritdoc}
     */
    public function getParameters()
    {
        return array();
    }
    
    /**
     * @{inheritdoc}
     */
    public function getSelections()
    {
        return array();
    }
    
    /**
     * @{inheritdoc}
     */
    public function isParameterMissing()
    {
        return false;
    }
    
    /**
     * @{inheritdoc}
     */
    public function updateParameters(array $choices)
    {
        
    }

}

