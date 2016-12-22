<?php

namespace Unit\Publisher\Selector\Parameter;

use Publisher\Requestor\RequestorInterface;
use Publisher\Storage\StorageInterface;
use Publisher\Storage\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session as HttpFoundationSession;

abstract class AbstractSelectorTest extends \PHPUnit_Framework_TestCase
{
    
    protected $selector;
    
    public function testInitiatedStatus()
    {
        $this->selector = $this->getSelector(
                $this->getRequestor(),
                $this->getStorage()
        );
        
        $this->assertNull($this->selector->getParameters());
        $this->assertSame(array(), $this->selector->getSelections());
        $this->assertTrue($this->selector->isParameterMissing());
    }
    
    /**
     * @dataProvider getFinalState
     */
    public function testNoParameterIsMissing(
            array $previousChoices,
            array $expectedParameters,
            $response = null
    ) {
        $this->selector = $this->getSelector(
                $this->getRequestor($response),
                $this->getStorage()
        );
        
        $this->selector->updateParameters($previousChoices);
        
        $this->assertFalse($this->selector->isParameterMissing());
        $this->assertSame($expectedParameters, $this->selector->getParameters());
    }
    
    protected function getUpdatedSelection($previousChoices, $response)
    {
        $requestor = $this->getRequestor($response);
        
        $this->selector = $this->getSelector($requestor, $this->getStorage());
        
        $this->selector->updateParameters($previousChoices);
        
        return $this->selector->getSelections();
    }
    
    protected abstract function getSelector(
            RequestorInterface $requestor,
            StorageInterface $storage
    );
    
    protected function getRequestor($response = null)
    {
        $requestor = $this->getMock('\Publisher\Requestor\RequestorInterface');
        
        if ($response !== null) {
            $requestor->expects($this->once())
                ->method('doRequest')
                ->willReturn($response);
        }
        
        return $requestor;
    }
    
    protected function getStorage()
    {
        $session = new HttpFoundationSession(new MockArraySessionStorage());
        return new Session($session);
    }
    
}