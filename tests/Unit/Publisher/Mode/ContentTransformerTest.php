<?php

namespace Unit\Publisher\Mode;

use PHPUnit\Framework\TestCase;
use Publisher\Mode\ContentTransformer;
use Publisher\Supervision\PublisherSupervisor;

class ContentTransformerTest extends TestCase
{
    
    public function setUp()
    {
        $config = array(
            'entries' => array(
                'Service' => array('User', 'Page')
            ),
            'modes' => array(
                'Foo'
            )
        );
        $supervisor = new PublisherSupervisor($config);
        
        $this->contentTransformer = new ContentTransformer($supervisor);
    }
    
    public function testTransformContent()
    {
        $modeData = array(
            0 => array(
                'entry' => 'ServiceUser',
                'content' => array(
                    'message' => 'Foo'
                )
            ),
            1 => array(
                'entry' => 'ServicePage',
                'content' => array(
                    'message' => 'Foo'
                ),
                'parameters' => array(
                    'pageId' => '0987654321',
                    'token' => '1234567890abc'
                )
            )
        );
        
        /* The EntryModeEntities ServiceUserFoo and ServicePageFoo
         * map the message parameter to 'status'.
         */
        $expectedData = $modeData;
        $expectedData[0]['content'] = array(
            'status' => 'Foo'
        );
        /* The data for ServiceUser doesn't provide any parameter data
         * but since the publisher manager ask for the parameter data
         * sooner or later we'll provide the key.
         */
        $expectedData[0]['parameters'] = array();
        $expectedData[1]['content'] = array(
            'status' => 'Foo'
        );
        
        $this->assertEquals(
            $expectedData,
            $this->contentTransformer->transform('Foo', $modeData)
        );
    }
    
}