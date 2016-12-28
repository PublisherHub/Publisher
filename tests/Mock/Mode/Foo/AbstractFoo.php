<?php

namespace Publisher\Mode\Foo;

use Publisher\Mode\AbstractEntryModeEntity;

abstract class AbstractFoo extends AbstractEntryModeEntity
{
    
    /**
     * @var string
     */
    protected $message;
    
    /**
     * @param string[] $content
     */
    public function __construct(array $content = array())
    {
        $this->message = isset($content['message']) ? $content['message'] : '';
    }
    
}