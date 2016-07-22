<?php

namespace Publisher\Selector;

class Selection
{
    protected $name;
    protected $choices;
    
    public function __construct($name, array $choices = array())
    {
        $this->name = $name;
        $this->choices = $choices;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getChoices()
    {
        return $this->choices;
    }
    
}