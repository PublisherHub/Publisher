<?php

namespace Unit\Helper;

use Publisher\Helper\EntryHelper;
use Publisher\Entry\EntryInterface;

use Publisher\Entry\OAuth2\FacebookUserEntry;
use Publisher\Entry\OAuth1\TwitterUserEntry;
use Publisher\Entry\OAuth1\XingUserEntry;
use Publisher\Entry\OAuth1\XingForumEntry;

class EntryHelperTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @dataProvider getEntryName
     */
    public function testIsEntryName($name, $isEntry)
    {
        $this->assertEquals($isEntry, EntryHelper::isEntryName($name));
    }
    
    public function getEntryName()
    {
        return array(
            array('FacebookUserEntry', true),
            array('\\Publisher\\Entry\\OAuth2\\FacebookUserEntry', false),
            array('ServiceUserEntry', false)
        );
    }
    
    /**
     * @dataProvider getEntry
     */
    public function testGetServiceName(EntryInterface $entry, $serviceName)
    {
        $this->assertEquals($serviceName, EntryHelper::getServiceName($entry));
    }
    
    public function getEntry()
    {
        return array(
            array(new FacebookUserEntry(), 'Facebook'),
            array(new TwitterUserEntry(), 'Twitter'),
            array(new XingForumEntry(array('forum_id' => 'foo')), 'Xing'),
            array(new XingUserEntry(), 'Xing')
        );
    }
    
    /**
     * @dataProvider getEntryWithScopes
     */
    public function testGetPublisherScopes($entry, $scopes)
    {
        $this->assertEquals($scopes, EntryHelper::getPublisherScopes($entry));
    }
    
    public function getEntryWithScopes()
    {
        return array(
            array(new FacebookUserEntry(), FacebookUserEntry::getPublisherScopes()),
            array(new TwitterUserEntry(), array()),
            array(new XingForumEntry(array('forum_id' => 'foo')), array()),
            array(new XingUserEntry(), array()),
            
        );
    }
}