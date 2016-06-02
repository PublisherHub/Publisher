<?php

namespace Publisher\Entry\OAuth1;

use Publisher\Entry\AbstractEntry;
use Publisher\Validator;

/*
 * @link https://dev.twitter.com/rest/reference/post/statuses/update
 */
class TwitterUserEntry extends AbstractEntry
{
    
    const MAX_LENGTH_OF_MESSAGE = 140;
    
    public function __construct(array $parameters = array())
    {
        parent::__construct($parameters);
        $this->path = 'statuses/update.json';
        $this->method = 'POST';
        $this->contentType = '';//'application/json';
    }
    
    protected function validateBody(array $body)
    {
        Validator::checkRequiredParameters($body, array('status'));
        Validator::validateMessageLength($body['status'], self::MAX_LENGTH_OF_MESSAGE);
    }
    
    // Implementation of MonitoredInterface
    
    public static function succeeded($response)
    {
        $response = json_decode($response);
        return (isset($response->id_str));
    }
    
    // Implementation of RecommendationInterface
    
    public function setRecommendationParameters(
            $message,
            $title = '',
            $url = '',
            $date = ''
    ) {
        $this->body['status'] = $message;
        
        if (!empty($title)) {
            $this->body['status'] = $title."\n".$this->body['status'];
        }
        if (!empty($url)) {
            $this->body['status'] .= "\n".$url;
        }
        if (!empty($date)) {
            $this->body['status'] .= "\n$date";
        }
    }

}
