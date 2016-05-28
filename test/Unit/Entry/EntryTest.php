<?php

namespace Unit\Entry;

abstract class EntryTest extends \PHPUnit_Framework_TestCase
{
    protected function getEntry(array $body = array(), array $parameters = array())
    {
        $entryName = $this->getEntryName();
        $entry =  new $entryName($parameters);
        $entry->setBody($body);
        
        return $entry;
    }
    
    protected abstract function getEntryName();
    
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
            $body = $this->entry->getBody();
        } catch (Exception $exception) {}
        
        $this->assertNull($exception);
    }
    
    /**
     * @dataProvider getInvalidBody
     * 
     * @expectedException \Publisher\Exception\MissingRequiredParameterException
     */
    public function testInvalidBody(array $body, array $parameters = array())
    {
        $this->entry = $this->getEntry($body, $parameters);
        $body = $this->entry->getBody();
    }
    
    /**
     * @dataProvider getBodyWithExceededMessage
     * 
     * @expectedException \Publisher\Exception\LengthException
     */
    public function testExceededMaxMessageLength(array $body, array $parameters = array())
    {
        $this->entry = $this->getEntry($body, $parameters);
        $body = $this->entry->getBody();
    }
    
    
    protected function getExceededMessage()
    {
        $entryName = $this->getEntryName();
        
        $maxLength = $entryName::MAX_LENGTH_OF_MESSAGE;
        $message = '';
        for ($i = 0; $i <= $maxLength; $i++) {
            $message .= 'c';
        }
        
        return $message;
    }
    
}