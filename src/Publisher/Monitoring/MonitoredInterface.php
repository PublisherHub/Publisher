<?php

namespace Publisher\Monitoring;

interface MonitoredInterface
{
    
    /**
     * Monitored objects give feedback wheter or not an operation,
     * call or request was successful based on the outcome ($result).
     * 
     * @return bool
     */
    public static function succeeded($result);
    
}
