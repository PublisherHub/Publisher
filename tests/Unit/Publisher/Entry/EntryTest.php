<?php

namespace Unit\Publisher\Entry;

abstract class EntryTest extends \PHPUnit_Framework_TestCase
{
    protected function getEntry(array $body = array(), array $parameters = array())
    {
        $entryName = $this->getEntryClass();
        $entry =  new $entryName($parameters);
        $entry->setBody($body);
        
        return $entry;
    }
    
    protected abstract function getEntryClass();
    
    public abstract function getValidBody();
    
    public abstract function getInvalidBody();
    
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