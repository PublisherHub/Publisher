<?php

namespace Publisher\Entry\Service;

use Publisher\Entry\AbstractEntry;
use Publisher\Helper\Validator;

class ServiceUserEntry extends AbstractEntry
{
    
    const MAX_LENGTH_OF_MESSAGE = 42; 
    
    protected function defineRequestProperties()
    {
        $this->request->setPath('/me/feed');
        $this->request->setMethod('POST');
    }

    protected function validateBody(array $body)
    {
        Validator::checkRequiredParametersAreSet($body, array('message'));
        if (isset($body['message'])) {
            Validator::validateMessageLength($body['message'], self::MAX_LENGTH_OF_MESSAGE);
        }
    }

    public static function succeeded($response)
    {
        
    }
    
}
