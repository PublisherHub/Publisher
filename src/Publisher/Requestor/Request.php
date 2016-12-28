<?php

namespace Publisher\Requestor;

class Request
{
    
    protected $path;
    protected $method;
    protected $body;
    protected $headers;
    
    public function __construct(
            string $path = '',
            string $method = 'GET',
            array $body = array(),
            array $headers = array()
    ) {
        $this->path = $path;
        $this->method = $method;
        $this->body = $body;
        $this->headers = $headers;
    }
    
    public function setPath(string $path)
    {
        $this->path = $path;
    }
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function setMethod(string $method)
    {
        $this->method = $method;
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function setBody(array $body)
    {
        $this->body = $body;
    }
    
    public function getBody()
    {
        return $this->body;
    }
    
    public function addHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }
    
    public function getHeaders()
    {
        return $this->headers;
    }
    
}

