<?php

namespace Publisher\OAuth1;

use OAuth\OAuth1\Service\Twitter as BaseTwitterService;
use Publisher\Validator;

class Twitter extends BaseTwitterService
{
    
    const MAX_LENGTH_OF_MESSAGE = 140;
    
    /**
     * {@inheritdoc}
     * 
     * @link https://dev.twitter.com/rest/reference/post/statuses/update
     */
    public function publish(array $body, array $extraHeaders = array())
    {
        $this->validateBody($body);
        return $this->request('statuses/update.json', 'POST', $body, $extraHeaders);
    }
    
    /**
     * @link https://dev.twitter.com/rest/reference/post/statuses/update
     */
    public function validateBody($body, $flag = 0)
    {
        switch ($flag) {
            case 0:
                $this->validateBodyForProfile($body);
                break;
            case 1:
                $this->validateBodyForOtherProfile($body);
                break;
        }
    }
    
    
    public function validateBodyForProfile(array $body)
    {
        Validator::checkRequiredParameters($body, array('status'));
        Validator::validateMessageLength($body['status'], self::MAX_LENGTH_OF_MESSAGE);
    }
}