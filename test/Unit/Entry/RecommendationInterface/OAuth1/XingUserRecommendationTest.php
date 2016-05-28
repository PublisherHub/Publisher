<?php

namespace Unit\Entry\RecommendationInterface\OAuth1;

//use Unit\Entry\RecommendationInterface\RecommendationTest;

class XingUserRecommendationTest extends RecommendationTest
{
    
    protected function getEntryName()
    {
        return 'Publisher\\Entry\\OAuth1\XingUserEntry';
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
                    'message' => "title\nmessage\nurl@foo.com\n21.05.2016"
                )
             )
        );
    }
}