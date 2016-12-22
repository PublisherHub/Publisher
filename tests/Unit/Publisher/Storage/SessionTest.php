<?php

namespace Unit\Publisher\Storage;

use Publisher\Storage\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session as HttpFoundationSession;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->session = new HttpFoundationSession(new MockArraySessionStorage());
        $this->storage = new Session($this->session);
    }
    
    public function tearDown()
    {
        $this->storage->clearAll();
    }
    
    /**
     * @dataProvider getTestData
     */
    public function testSetAndGet($requestedKey, $value)
    {
        $clientId = 'Client1';
        $this->storage->registerClient($clientId);
        $this->storage->set($clientId, $requestedKey, $value);
        $this->assertSame($value, $this->storage->get($clientId, $requestedKey));
    }
    
    public function getTestData()
    {
        return array(
            array('results', array()),
            array('results', array('a', 'b', 'c'))
        );
    }
    
    public function testGetNoData()
    {
        $clientId = 'Client1';
        $requestedKey = 'results';
        
        $this->assertNull($this->storage->get($clientId, $requestedKey));
        
        $this->storage->registerClient($clientId);
        $this->assertNull($this->storage->get($clientId, $requestedKey));
    }
    
    public function testClearAllClientStorages()
    {
        $clientIds = array('Client1', 'Client2', 'Client3');
        
        foreach ($clientIds as $clientId) {
            $this->storage->registerClient($clientId);
        }
        foreach ($clientIds as $clientId) {
            $this->assertArrayHasKey($clientId, $this->getStorage());
        }
        
        $this->storage->clearAll();
        
        $this->assertEquals(null, $this->getStorage());
        $this->assertNull($this->getStorage());
        
    }
    
    protected function getStorage()
    {
        return $this->session->get('Publisher/ClientStorage');
    }
}