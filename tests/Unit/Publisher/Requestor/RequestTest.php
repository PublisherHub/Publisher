<?php

namespace Unit\Publisher\Requestor;

use Publisher\Requestor\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    
    public function testMinimalConstructor()
    {
        $request = new Request();
        
        $this->assertSame('', $request->getPath());
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame(array(), $request->getBody());
        $this->assertSame(array(), $request->getHeaders());
    }
    
    public function testConstructor()
    {
        $path = '/user/me/groups';
        $method = 'POST';
        $body = array('foo' => 'bar');
        $headers = array('Content-Type' => 'application/json');
        
        $request = new Request($path, $method, $body, $headers);
        
        $this->assertSame($path, $request->getPath());
        $this->assertSame($method, $request->getMethod());
        $this->assertSame($body, $request->getBody());
        $this->assertSame($headers, $request->getHeaders());
    }
    
    public function testSetAndGet()
    {
        $request = new Request();
        
        $request->setPath('/path/to/foo');
        $request->setMethod('POST');
        $request->setBody(array('parameter' => 'bar'));
        
        $this->assertSame('/path/to/foo', $request->getPath());
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame(array('parameter' => 'bar'), $request->getBody());
    }
    
    public function testAddHeaders()
    {
        $request = new Request();
        
        $request->addHeaders(array('Authorization' => 'OAuth w1s2a3d4'));
        $this->assertSame(
                array('Authorization' => 'OAuth w1s2a3d4'),
                $request->getHeaders()
        );
        // Override existing parameters
        $request->addHeaders(array('Authorization' => 'OAuth wasd1234'));
        $this->assertSame(
                array('Authorization' => 'OAuth wasd1234'),
                $request->getHeaders()
        );
        // add more parameters
        $request->addHeaders(array('Content-Type' => 'application/json'));
        $this->assertSame(
                array(
                    'Authorization' => 'OAuth wasd1234',
                    'Content-Type' => 'application/json'
                ),
                $request->getHeaders()
        );
        
    }
    
}

