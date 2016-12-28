<?php

namespace Unit\Publisher\Supervision;

use Publisher\Supervision\PublisherSupervisor;

class PublisherSupervisorCheckConfigTest extends \PHPUnit_Framework_TestCase
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
                'Service' => array('Forum') // ServiceForumEntry doesn't exists
            ),
            'modes' => array(
                'Text' // the AbstractEntryModeEntity AbstractText doesn't exists
            )
            // the EntryModeEntity ServiceForumText doesn't exists
        );
        
        $notFound = array(
            'entries' => array('\\Publisher\\Entry\\Service\\ServiceForumEntry'),
            'modes' => array('\\Publisher\\Mode\\Text\\AbstractText'),
            'entities' => array(
                '\\Publisher\\Entry\\Service\\Mode\\Text\\ServiceForumText'
            )
        );
        
        $supervisor = new PublisherSupervisor($config);
        
        $this->assertEquals($notFound, $supervisor->checkConfig());
    }
    
}