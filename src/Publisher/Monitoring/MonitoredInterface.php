<?php

namespace Publisher\Monitoring;

interface MonitoredInterface
{
    
    /**
     * Monitored objects give feedback wheter or not an operation,
     * call or request was successful based on the $response.
     * 
     * @return bool
     */
    public static function succeeded($response);
    
}
