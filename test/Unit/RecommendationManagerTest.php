<?php

namespace Unit;

use Unit\AbstractPublishingManagerTest;
use Publisher\Interfaces\PublisherFactoryInterface;
use Publisher\Monitoring\Monitor;
use Publisher\RecommendationManager;

class RecommendationManagerTest extends AbstractPublishingManagerTest
{
    
    protected function getManager(
            array $entryData,
            PublisherFactoryInterface $publisherFactory,
            Monitor $monitor
    ) {
        return new RecommendationManager($entryData, $publisherFactory, $monitor);
    }
    
    protected function getTestContent()
    {
        return array(
            'title' => 'foo',
            'message' => 'foo',
            'url' => 'foo',
            'date' => 'foo'
        );
    }
}