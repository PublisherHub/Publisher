<?php

namespace Publisher\Entry\OAuth1;

use Publisher\Entry\AbstractEntry;
use Publisher\Entry\Interfaces\RecommendationInterface;
use Publisher\Validator;

/**
 * @link https://dev.xing.com/docs/post/groups/forums/:forum_id/posts
 */
class XingForumEntry extends AbstractEntry implements RecommendationInterface
{
    
    const MAX_LENGTH_OF_MESSAGE = 420;
    static $validImageMimeTypes = array('image/jpeg', 'image/png', 'image/gif', 'image/bmp');
    
    public function __construct(array $parameters = array())
    {
        parent::__construct($parameters);
        
        $this->path = '/groups/forums/?/posts';
        Validator::checkRequiredParameters($parameters, array('forum_id'));
        $this->addForumIdToPath($parameters['forum_id']);
        
        $this->method = 'POST';
        $this->contentType = 'application/json';
    }
    
    protected function addForumIdToPath($forumId)
    {
        $this->path = preg_replace('/(\?)/', $forumId, $this->path);
    }
    
    protected function validateBody(array $body)
    {
        Validator::checkRequiredParameters($body, array('content', 'title'));
        
        if (isset($body['image'])) {
            Validator::checkRequiredParameters(
                    $body['image'],
                    array('file_name', 'mime_type', 'content')
            );
            Validator::validateValue($body['image']['mime_type'], self::$validImageMimeTypes);
        }
        
        Validator::validateMessageLength($body['content'], self::MAX_LENGTH_OF_MESSAGE);
    }

    public function setRecommendationParameters(
            $message,
            $title = '',
            $url = '', 
            $date = ''
    ) {
        $this->body['content'] = utf8_decode($message);
        
        $this->body['title'] = utf8_decode($title);
        
        if (!empty($url)) {
            $this->body['content'] .= "\n".$url;
        }
        if (!empty($date)) {
            $this->body['content'] .= "\n$date";
        }
    }
}