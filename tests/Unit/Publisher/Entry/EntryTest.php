<?php

namespace Unit\Publisher\Entry;

use Publisher\Entry\EntryInterface;

abstract class EntryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $body
     * @param array $parameters
     * 
     * @return EntryInterface
     */
    protected function getEntry(array $body = array(), array $parameters = array())
    {
        $entryName = $this->getEntryClass();
        $entry =  new $entryName($parameters);
        $entry->setBody($body);
        
        return $entry;
    }
    
    /**
     * @return string full class name
     */
    protected abstract function getEntryClass();
    
    public function testGetPublisherScopes()
    {
        $entryClass = $this->getEntryClass();
        
        $this->assertEquals(
            $this->getExpectedPublisherScopes(),
            $entryClass::getPublisherScopes()
        );
    }
    
    /**
     * @return array
     */
    protected function getExpectedPublisherScopes()
    {
        return array();
    }

    /**
     * @return array
     */
    public abstract function getValidBody();
    
    /**
     * @return array
     */
    public abstract function getInvalidBody();
    
    /**
     * @return array
     */
    public abstract function getBodyWithExceededMessage();
    
    /**
     * @dataProvider getValidBody
     */
    public function testValidBody(array $body, array $parameters = array())
    {
        $exception = null;
        try {
            $this->entry = $this->getEntry($body, $parameters);
            $request = $this->entry->getRequest();
        } catch (Exception $exception) {}
        
        $this->assertNull($exception);
    }
    
    /**
     * @dataProvider getInvalidBody
     * 
     * @expectedException \Publisher\Helper\Exception\MissingRequiredParameterException
     */
    public function testInvalidBody(array $body, array $parameters = array())
    {
        $this->entry = $this->getEntry($body, $parameters);
        $body = $this->entry->getBody();
    }
    
    /**
     * @dataProvider getBodyWithExceededMessage
     * 
     * @expectedException \Publisher\Helper\Exception\LengthException
     */
    public function testExceededMaxMessageLength(array $body, array $parameters = array())
    {
        $this->entry = $this->getEntry($body, $parameters);
        $body = $this->entry->getBody();
    }
    
    /**
     * @return string
     */
    protected function getExceededMessage()
    {
        $entryName = $this->getEntryClass();
        
        $maxLength = $entryName::MAX_LENGTH_OF_MESSAGE;
        $message = '';
        for ($i = 0; $i <= $maxLength; $i++) {
            $message .= 'c';
        }
        
        return $message;
    }
    
}