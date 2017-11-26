<?php

namespace Unit\Publisher\Entry\Factory;

use PHPUnit\Framework\TestCase;
use Publisher\Entry\Factory\EntryFactory;
use Publisher\Helper\EntryHelperInterface;
use Publisher\Entry\Service\ServiceForumEntry;

class EntryFactoryTest extends TestCase
{
    
    public function testGetEntry()
    {
        $entryId = 'ServiceForum';
        
        $entryHelper = $this->getMockBuilder(EntryHelperInterface::class)->getMock();
        $entryHelper
            ->expects($this->once())
            ->method('getEntryClass')
            ->with($entryId)
            ->willReturn(ServiceForumEntry::class);
        
        $entryFactory = new EntryFactory($entryHelper);
        $entry = $entryFactory->getEntry($entryId, ['forumId' => 'foo']);
        
        $this->assertInstanceOf(ServiceForumEntry::class, $entry);
        $this->assertEquals('/forum/foo', $entry->getRequest()->getPath());
    }
    
}
