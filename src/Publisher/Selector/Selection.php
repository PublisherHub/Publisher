<?php

namespace Publisher\Selector;

class Selection
{
    protected $name;
    protected $choices;
    
    public function __construct($name, array $choices = array()) // @todo require choices or comment why it shouldn't be necessary
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