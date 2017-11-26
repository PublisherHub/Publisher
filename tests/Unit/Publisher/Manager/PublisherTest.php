<?php

namespace Unit\Publisher\Manager;

use Unit\Publisher\Manager\AbstractPublisherTest;

class PublisherTest extends AbstractPublisherTest
{
    
    public function getTestContent()
    {
        return array(
            array(
                array(
                //nothing because it's for a mock
                )
            )   
        );
    }
    
    /**
     * Returns the mode Id that is used for this test class.
     * 
     * @return string
     */
    protected function getModeId()
    {
        return 'Foo';
    }
    
}