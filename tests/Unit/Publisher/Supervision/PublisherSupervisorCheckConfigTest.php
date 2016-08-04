<?php

namespace Unit\Publisher\Supervision;

use Publisher\Supervision\PublisherSupervisor;

class PublisherSupervisorCheckConfigTest extends \PHPUnit_Framework_TestCase
{
    
    public function testAllFound()
    {
        $notFound = array(
            'entries' => array(),
            'modes' => array()
        );
        
        $config = array(
            'entryIds' => array(
                'Mock' => array('User', 'Page')
            ),
            'modes' => array(
                'Mock'
            )
        );
        
        $supervisor = new PublisherSupervisor($config);
        
        $this->assertEquals($notFound, $supervisor->checkConfig());
    }
    
    public function testNotFound()
    {
        $notFound = array(
            'entries' => array('\\Publisher\\Entry\\Mock\\MockForumEntry'),
            'modes' => array(
                '\\Publisher\\Mode\\MockText\\MockTextMode',
                '\\Publisher\\Mode\\MockText\\MockTextInterface'
            )
        );
        
        $config = array(
            'entryIds' => array(
                'Mock' => array('Forum') // MockForumEntry doesn't exists
            ),
            'modes' => array(
                'MockText' // MockTextMode, MockTextInterface doesn't exists
            )
        );
        
        $supervisor = new PublisherSupervisor($config);
        
        $this->assertEquals($notFound, $supervisor->checkConfig());
    }
    
}