<?php

namespace Unit\Entry\RecommendationInterface\OAuth2;

// use \Unit\Entry\RecommendationInterface\RecommendationTest;
use Unit\Entry\RecommendationInterface\OAuth1\RecommendationTest;

class FacebookUserRecommendationTest extends RecommendationTest
{
    
    protected function getEntryName()
    {
        return 'Publisher\\Entry\\OAuth2\FacebookUserEntry';
    }
    
    public function getValidRecommendationParameters()
    {
        return array(
            array('message', '', '', ''),
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
                    'message' => "title\nmessage\n21.05.2016",
                    'link' => 'url@foo.com'
                )
            )
        );
    }
}