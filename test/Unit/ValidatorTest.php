<?php

namespace Unit;

use Publisher\Validator;
use Publisher\Exception\LengthException;
use Publisher\Exception\MissingRequiredParameterException;
use Publisher\Exception\ValueException;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    
    public function testValidMessageLength()
    {
        $exception = null;
        try {
            Validator::validateMessageLength('foo', 3);
            Validator::validateMessageLength('   ', 3);
            $valid = true;
        } catch (LengthException $exception) {}
        
        $this->assertNull($exception);
    }
    
    /**
     * @expectedException \Publisher\Exception\LengthException
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
            Validator::checkRequiredParameters($given, $required);
            $required[] = 'bar';
            Validator::checkRequiredParameters($given, $required);
        } catch (MissingRequiredParameterException $exception) {}
        
        $this->assertNull($exception);
    }
    
    /**
     * @dataProvider getNotAllRequiredParameters
     * 
     * @expectedException \Publisher\Exception\MissingRequiredParameterException
     */
    public function testMissingRequiredParameters($given)
    {
        $required = array('foo', 'bar');
        
        Validator::checkRequiredParameters($given, $required);
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
     * @dataProvider getNoRequiredParameters
     * 
     * @expectedException \Publisher\Exception\MissingRequiredParameterException
     */
    public function testMissingAnyRequiredParameterGiven($given)
    {
        $required = array('foo', 'bar');
        
        Validator::checkAnyRequiredParameter($given, $required);
    }
    
    public function getNoRequiredParameters()
    {
        return array(
            array(array()),
            array(array('anotherParameter' => 'value'))
        );
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
     * @expectedException \Publisher\Exception\ValueException
     */
    public function testInValidValue()
    {
        $allowed = array('foo', 'bar');
        $given = 'notFoo';
        
        Validator::validateValue($given, $allowed);
    }
}