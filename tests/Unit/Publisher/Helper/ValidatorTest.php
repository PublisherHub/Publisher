<?php

namespace Unit\Publisher\Helper;

use Publisher\Helper\Validator;
use Publisher\Helper\Exception\LengthException;
use Publisher\Helper\Exception\MissingRequiredParameterException;
use Publisher\Helper\Exception\ValueException;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    
    public function testValidMessageLength()
    {
        $exception = null;
        try {
            Validator::validateMessageLength('foo', 3);
            Validator::validateMessageLength('   ', 3);
            
        } catch (LengthException $exception) {}
        
        $this->assertNull($exception);
    }
    
    /**
     * @expectedException \Publisher\Helper\Exception\LengthException
     */
    public function testExceededMessageLength()
    {
        Validator::validateMessageLength('foo ', 3);
    }
    
    public function testRequiredParameters()
    {
        $given = array('foo' => '1', 'bar' => 2);
        $required = array('foo');
        
        $exception = null;
        try {
            Validator::checkRequiredParametersAreSet($given, $required);
            $required[] = 'bar';
            Validator::checkRequiredParametersAreSet($given, $required);
        } catch (MissingRequiredParameterException $exception) {}
        
        $this->assertNull($exception);
    }
    
    /**
     * @dataProvider getNotAllRequiredParameters
     * 
     * @expectedException \Publisher\Helper\Exception\MissingRequiredParameterException
     */
    public function testMissingRequiredParameters($given)
    {
        $required = array('foo', 'bar');
        
        Validator::checkRequiredParametersAreSet($given, $required);
    }
    
    public function getNotAllRequiredParameters()
    {
        return array(
            array(array()),
            array(array('foo'))
        );
    }
    
    /**
     * @dataProvider getAnyRequiredParameter
     */
    public function testAnyRequiredParameterGiven($given)
    {
        $required = array('foo', 'bar');
        
        $exception = null;
        try {
            Validator::checkAnyRequiredParameter($given, $required);
        } catch (MissingRequiredParameterException $ex) {}
        
        $this->assertNull($exception);
    }
    
    public function getAnyRequiredParameter()
    {
        return array(
            array(array('foo' => 'value')),
            array(array('foo' => 'value', 'anotherParameter' => 'value2')),
            array(array('foo' => 'value', 'anotherParameter' => 'value2', 'bar' => 'value3')),
            array(array('anotherParameter' => 'value2', 'bar'  => 'value3')),
            array(array('bar' => 'value3'))
        );
    }
    
    /**
     * @dataProvider getNotRequiredParameters
     * 
     * @expectedException \Publisher\Helper\Exception\MissingRequiredParameterException
     */
    public function testMissingAnyRequiredParameterGiven($given)
    {
        $required = array('foo', 'bar');
        
        Validator::checkAnyRequiredParameter($given, $required);
    }
    
    public function getNotRequiredParameters()
    {
        return array(
            array(array()),
            array(array('anotherParameter' => 'value'))
        );
    }
    
    public function testCheckRequiredParameters()
    {
        $required = array('foo', 'bar');
        $given = array('foo' => '', 'bar' => null);
        
        Validator::checkRequiredParameters($given, $required);
    }
    
    /**
     * @expectedException \Publisher\Helper\Exception\MissingRequiredParameterException
     */
    public function testMissingRequiredParameter()
    {
        $required = array('foo', 'bar');
        $given = array();
        
        Validator::checkRequiredParameters($given, $required);
    }
    
    public function testValidValue()
    {
        $allowed = array('foo', 'bar');
        
        $exception = null;
        try {
            $given = 'foo';
            Validator::validateValue($given, $allowed);
        } catch (ValueException $ex) {}
        
        $this->assertNull($exception);
    }
    
    /**
     * @expectedException \Publisher\Helper\Exception\ValueException
     */
    public function testInValidValue()
    {
        $allowed = array('foo', 'bar');
        $given = 'notFoo';
        
        Validator::validateValue($given, $allowed);
    }
    
}