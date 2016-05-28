<?php

namespace Unit\Entry\RecommendationInterface\OAuth1;

abstract class RecommendationTest extends \PHPUnit_Framework_TestCase
{
    
    protected function getEntry(array $body = array(), array $parameters = array())
    {
        $entryName = $this->getEntryName();
        $entry = new $entryName($parameters);
        $entry->setBody($body);
        
        return $entry;
    }
    
    protected abstract function getEntryName();
    
    public abstract function getValidRecommendationParameters();
    
    public abstract function getRecommendationParametersAndResult();
    
    /**
     * @dataProvider getValidRecommendationParameters
     */
    public function testSuccessfulRecommandation($message, $title, $url, $date)
    {
        $this->entry = $this->getEntry();
        
        $exception = null;
        try {
        $this->entry->setRecommendationParameters($message, $title, $url, $date);
            $body = $this->entry->getBody();
        } catch (Exception $exception) {}
        
        $this->assertNull($exception);
    }
    
    /**
     * @dataProvider getRecommendationParametersAndResult
     */
    public function testObtainedRecommendationBody($message, $title, $url, $date, array $results)
    {
        $this->entry = $this->getEntry();
        
        $this->entry->setRecommendationParameters($message, $title, $url, $date);
        $body = $this->entry->getBody();
        
        foreach($results as $parameter => $result) {
            $this->assertEquals($result, $body[$parameter]);
        }
        
    }
}