<?php

namespace Unit\Publisher\Supervision;

use PHPUnit\Framework\TestCase;
use Publisher\Supervision\PublisherSupervisor;

class PublisherSupervisorCheckConfigTest extends TestCase
{
    
    public function testAllFound()
    {
        $config = array(
            'entries' => array(
                'Service' => array('User', 'Page')
            ),
            'modes' => array(
                'Foo'
            )
        );
        
        $notFound = array(
            'entries' => array(),
            'modes' => array(),
            'entities' => array()
        );
        
        $supervisor = new PublisherSupervisor($config);
        
        $this->assertEquals($notFound, $supervisor->checkConfig());
    }
    
    public function testNotFound()
    {
        $config = array(
            'entries' => array(
                'Service' => array('Group') // ServiceGroupEntry doesn't exists
            ),
            'modes' => array(
                'Text' // the AbstractEntryModeEntity AbstractText doesn't exists
            )
            // the EntryModeEntity ServiceForumText doesn't exists
        );
        
        $notFound = array(
            'entries' => array('\\Publisher\\Entry\\Service\\ServiceGroupEntry'),
            'modes' => array('\\Publisher\\Mode\\Text\\AbstractText'),
            'entities' => array(
                '\\Publisher\\Entry\\Service\\Mode\\Text\\ServiceGroupText'
            )
        );
        
        $supervisor = new PublisherSupervisor($config);
        
        $this->assertEquals($notFound, $supervisor->checkConfig());
    }
    
}