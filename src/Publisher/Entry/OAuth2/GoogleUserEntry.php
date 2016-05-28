<?php

namespace Publisher\Entry\OAuth2;

use Publisher\Entry\OAuth2\AbstractEntry;
use Publisher\Entry\Interfaces\RecommendationInterface;
use Publisher\Validator;

class GoogleUserEntry extends AbstractEntry implements RecommendationInterface
{
    protected function validateBody(array $body)
    {
        
    }

    public static function getPublisherScopes()
    {
        
    }

    public function setRecommendationParameters($message, $title = '', $url = '',
            $date = '')
    {
        
    }

}
