<?php

namespace Publisher\Entry\Interfaces;

interface RecommendationInterface
{
    
    public function setRecommendationParameters(
            $message,
            $title = '',
            $url = '',
            $date = ''
    );
}

