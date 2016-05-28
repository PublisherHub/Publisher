<?php

namespace Unit\Entry\RecommendationInterface\OAuth1;

//use Unit\Entry\RecommendationInterface\RecommendationTest;

class XingForumRecommendationTest extends RecommendationTest
{
    
    public function getEntry(array $body = array(), array $parameters = array())
    {
        /* 
         * Except 'forum_id'
         * all required parameters are set
         * when setRecommendationParameters() is called
         */
        $parameters = array('forum_id' => '');
        
        return parent::getEntry($body, $parameters);
    }
    
    protected function getEntryName()
    {
        return 'Publisher\\Entry\\OAuth1\XingForumEntry';
    }
    
    public function getValidRecommendationParameters()
    {
        return array(
            array('message', 'title', '', ''),
            array('message', 'title', 'url@foo.com', '21.05.2016')
        );
    }
    
    public function getRecommendationParametersAndResult()
    {
        return array(
            array(
                'message',
                'title',
                'url@foo.com',
                '21.05.2016',
                array(
                    'content' => "message\nurl@foo.com\n21.05.2016",
                    'title' => 'title'
                )
            )
        );
    }
}

