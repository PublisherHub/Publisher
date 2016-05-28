<?php

namespace Publisher\Entry\OAuth2;

use Publisher\Entry\OAuth2\AbstractEntry;
use Publisher\Entry\Interfaces\RecommendationInterface;
use Publisher\Validator;

/**
 * @link https://developers.facebook.com/docs/graph-api/reference/v2.6/user/feed
 * 
 * Even so the documentation says otherwise, it is possible to tag someone without giving a place.
 */
class FacebookUserEntry extends AbstractEntry implements RecommendationInterface
{
    
    const MAX_LENGTH_OF_MESSAGE = 63205;
    
    public function __construct(array $parameters = array())
    {
        parent::__construct($parameters);
        $this->path = '/me/feed';
        $this->method = 'POST';
        $this->contentType = '';//'application/json';
    }

    public static function getPublisherScopes()
    {
        return array('publish_actions');
    }
    
    protected function validateBody(array $body)
    {
        Validator::checkAnyRequiredParameter($body, array('message', 'link', 'place'));
        if (isset($body['message'])) {
            Validator::validateMessageLength($body['message'], self::MAX_LENGTH_OF_MESSAGE);
        }
    }

    // Implementation of RecommendationInterface
    
    public function setRecommendationParameters(
            $message,
            $title = '',
            $url = '',
            $date = ''
    ) {
        $this->body['message'] = $message;
        
        if (!empty($title)) {
            $this->body['message'] = $title."\n".$this->body['message'];
        }
        if (!empty($url)) {
            $this->body['link'] = $url;
        }
        if (!empty($date)) {
            $this->body['message'] .= "\n".$date;
        }
    }
    

}