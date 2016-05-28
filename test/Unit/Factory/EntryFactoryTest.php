<?php

namespace Unit\Factory;

use Publisher\Factory\EntryFactory;

class EntryFactoryTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @dataProvider getInvalidEntryNames
     * 
     * @expectedException \Publisher\Exception\UnknownEntryException
     */
    public function testInvalidEntryRequest($entryName)
    {
        $entry = EntryFactory::getEntry($entryName);
    }
    
    public function getInvalidEntryNames()
    {
        return array(
            array('Entry'),
            array('FacebookEntry'),
            array('\\Publisher\\Entry\\OAuth2\\FacebookUserEntry')
        );
    }
    
    /**
     * @dataProvider getValidEntryNames
     */
    public function testGetEntry($entryName, array $parameters, $fullClassName)
    {
        $entry = EntryFactory::getEntry($entryName, $parameters);
        $this->assertInstanceOf($fullClassName, $entry);
    }
    
    public function getValidEntryNames()
    {
        $prefix = '\\Publisher\\Entry\\OAuth';
        
        return array(
            array('FacebookUserEntry', array(), $prefix.'2\\FacebookUserEntry'),
            array('TwitterUserEntry', array(), $prefix.'1\\TwitterUserEntry'),
            array('XingUserEntry', array(), $prefix.'1\\XingUserEntry'),
            array('XingForumEntry', array('forum_id' => 'foo'), $prefix.'1\\XingForumEntry')
        );
    }
}