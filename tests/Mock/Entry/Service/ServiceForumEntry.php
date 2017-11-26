<?php

namespace Publisher\Entry\Service;

use Publisher\Entry\AbstractEntry;
use Publisher\Helper\Validator;

class ServiceForumEntry extends AbstractEntry
{
    
    const MAX_LENGTH_OF_MESSAGE = 42; 
    
    /**
     * @inheritDoc
     */
    protected function setParameters(array $parameters)
    {
        Validator::checkRequiredParameters($parameters, ['forumId']);
        
        $this->request->setPath('/forum/' . $parameters['forumId']);
    }
    
    /**
     * @inheritDoc
     */
    protected function defineRequestProperties()
    {
        //
    }

    /**
     * @inheritDoc
     */
    protected function validateBody(array $body)
    {
        //
    }

    /**
     * @inheritDoc
     */
    public static function succeeded($response)
    {
        //
    }
    
}
