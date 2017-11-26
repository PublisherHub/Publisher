<?php

namespace Publisher\Selector;

class Selection
{
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var array
     */
    protected $choices;
    
    /**
     * @param string $name
     * @param array  $choices
     */
    public function __construct($name, array $choices = [])
    {
        $this->name = $name;
        $this->choices = $choices;
    }
    
    /**
     * Returns the parameters name that the Selection belongs to.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Returns the available choices for the parameters value.
     * 
     * @return array
     */
    public function getChoices()
    {
        return $this->choices;
    }
    
}