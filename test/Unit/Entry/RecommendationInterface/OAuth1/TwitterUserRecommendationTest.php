<?php

namespace Unit\Entry\RecommendationInterface\OAuth1;

//use \Unit\Entry\RecommendationInterface\RecommendationTest as BaseTest;

class TwitterUserRecommendationTest extends RecommendationTest
{
    
    protected function getEntryName()
    {
        return 'Publisher\\Entry\\OAuth1\TwitterUserEntry';
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
                    'status' => "title\nmessage\nurl@foo.com\n21.05.2016"
                )
             )
        );
    }
}