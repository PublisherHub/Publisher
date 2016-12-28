<?php

namespace Publisher\Mode;

interface BodyGeneratorInterface
{
    
    /**
     * Generates the body for an specific Entry.
     * 
     * @return array
     */
    public function generateBody();
    
}