<?php

namespace Publisher;

use Publisher\AbstractPublishingManager;
use Publisher\Validator;
use Publisher\Entry\EntryInterface;

class RecommendationManager extends AbstractPublishingManager
{
    
    protected function validateContent(array $content)
    {
        $required = array('title', 'message', 'url', 'date');
        
        Validator::checkRequiredParameters($content, $required);
    }
    
    protected function fillEntry(EntryInterface $entry, array $content)
    {
        $entry->setRecommendationParameters(
                $content['title'],
                $content['message'],
                $content['url'],
                $content['date']
        );
    }
    
    protected function publish(EntryInterface $entry)
    {
        $service = $this->getPublisher($entry);
        
        return $service->publish($entry);
    }
}