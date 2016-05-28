<?php

namespace Unit\Entry\OAuth1;

use Unit\Entry\EntryTest;

class XingForumEntryTest extends EntryTest
{
    
    protected function getEntryName()
    {
        return 'Publisher\\Entry\\OAuth1\XingForumEntry';
    }
    
    public function getValidBody()
    {
        return array(
            array(
                array('content' => 'foo', 'title' => 'bar'),
                array('forum_id' => 'foo')
            ),
            array(
                array('content' => 'foo', 'title' => 'bar'),
                array('forum_id' => 'foo')
            ),
            array(
                array(
                    'content' => 'foo',
                    'title' => 'bar',
                    'image' => array(
                        'file_name' => 'foo',
                        'mime_type' => 'image/jpeg',
                        'content' => 'foo'
                    )
                ),
                array('forum_id' => 'foo')
            ),
            array(
                array(
                    'content' => 'foo',
                    'title' => 'bar',
                    'image' => array(
                        'file_name' => 'foo',
                        'mime_type' => 'image/png',
                        'content' => 'foo'
                    )
                ),
                array('forum_id' => 'foo')
            ),
            array(
                array(
                    'content' => 'foo',
                    'title' => 'bar',
                    'image' => array(
                        'file_name' => 'foo',
                        'mime_type' => 'image/gif',
                        'content' => 'foo'
                    )
                ),
                array('forum_id' => 'foo')
            ),
            array(
                array(
                    'content' => 'foo',
                    'title' => 'bar',
                    'image' => array(
                        'file_name' => 'foo',
                        'mime_type' => 'image/bmp',
                        'content' => 'foo'
                    )
                ),
                array('forum_id' => 'foo')
            )
        );
    }
    
    public function getInvalidBody()
    {
        return array(
            array(array()),
            array(array('notRequired' => 'foo')),
            array(array('content' => 'foo')),
            array(array('title' => 'bar')),
            array(array('content' => 'foo', 'title' => 'bar')),
            array(
                array('content' => 'foo', 'title' => 'bar', 'image' => array()),
                array('forum_id' => 'foo')
            )
        );
    }
    
    public function getBodyWithExceededMessage()
    {
        return array(
            array(
                array('content' => $this->getExceededMessage(), 'title' => ''),
                array('forum_id' => '')
            )
        );
    }
    
    public function testAddForumIdToPath()
    {
        $parameters = array('forum_id' => 'foo');
        $body = array('content' => '', 'title' => '');
        
        $this->entry = $this->getEntry($body, $parameters);
        
        $this->assertEquals('/groups/forums/foo/posts', $this->entry->getPath());
    }
}

