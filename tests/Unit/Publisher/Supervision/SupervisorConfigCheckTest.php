<?php

namespace Unit\Publisher\Supervision;

use PHPUnit\Framework\TestCase;
use Publisher\Supervision\SupervisorConfigCheck;
use Publisher\Supervision\PublisherSupervisor;

class SupervisorConfigCheckTest extends TestCase
{
    
    public function testAllFound()
    {
        $config = [
            'entries' => [
                'Service' => ['User', 'Page']
            ],
            'modes' => [
                'Foo'
            ]
        ];
        
        $notFound = [
            'entries' => [],
            'modes' => [],
            'entities' => []
        ];
        
        $supervisor = new PublisherSupervisor($config);
        
        $this->assertEquals($notFound, SupervisorConfigCheck::checkConfig($supervisor));
    }
    
    public function testNotFound()
    {
        $config = [
            'entries' => [
                'Service' => ['Group'] // ServiceGroupEntry doesn't exists
            ],
            'modes' => [
                'Text' // the AbstractEntryModeEntity AbstractText doesn't exists
            ]
            // the EntryModeEntity ServiceForumText doesn't exists
        ];
        
        $notFound = [
            'entries' => ['\\Publisher\\Entry\\Service\\ServiceGroupEntry'],
            'modes' => ['\\Publisher\\Mode\\Text\\AbstractText'],
            'entities' => [
                '\\Publisher\\Entry\\Service\\Mode\\Text\\ServiceGroupText'
            ]
        ];
        
        $supervisor = new PublisherSupervisor($config);
        
        $this->assertEquals($notFound, SupervisorConfigCheck::checkConfig($supervisor));
    }
    
}