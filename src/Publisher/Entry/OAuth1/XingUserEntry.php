<?php

namespace Publisher\Entry\OAuth1;

use Publisher\Entry\AbstractEntry;
use Publisher\Validator;

/**
 * @link https://dev.xing.com/docs/post/users/:id/status_message
 * 
 * Publish will return "Status update has been posted" it was successful.
 */
class XingUserEntry extends AbstractEntry
{
    
    const MAX_LENGTH_OF_MESSAGE = 420;
    static $validImageMimeTypes = array('image/jpeg', 'image/png', 'image/gif', 'image/bmp');
    
    public function __construct(array $body = array())
    {
        parent::__construct($body);
        $this->path = '/users/me/status_message';
        $this->method = 'POST';
        $this->contentType = '';//'application/json';
    }
    
    protected function validateBody(array $body)
    {
        Validator::checkRequiredParameters($body, array('message'));
        Validator::validateMessageLength($body['message'], self::MAX_LENGTH_OF_MESSAGE);
    }
    
    // Implementation of MonitoredInterface
    
    public static function succeeded($response)
    {
        return ($response === 'Status update has been posted');
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
            $this->body['message'] .= "\n".$url;
        }
        if (!empty($date)) {
            $this->body['message'] .= "\n$date";
        }
    }
    
}