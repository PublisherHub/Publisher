<?php

namespace Publisher\Helper;

use Publisher\Helper\Exception\LengthException;
use Publisher\Helper\Exception\MissingRequiredParameterException;
use Publisher\Helper\Exception\RequiredParameterFound;
use Publisher\Helper\Exception\ValueException;

class Validator
{
    static function validateMessageLength($message, $maxLength)
    {
        if (mb_strlen($message) > $maxLength) {
            throw new LengthException(
                    "The maximum length is $maxLength characters."
            );
        }
    }
    
    /**
     * Validates if all required parameters are set.
     * 
     * @param array $given
     * @param array $required contains required keys
     */
    static function checkRequiredParameters(array $given, array $required)
    {
        foreach ($required as $key) {
            if (empty($given[$key])) {
                throw new MissingRequiredParameterException(
                        "Missing required parameter '$key'"
                );
            }
        }
    }
    
    /**
     * Validates if any required parameters are set and not empty.
     * 
     * @param array $given
     * @param array $required contains required keys
     */
    static function checkAnyRequiredParameter(array $given, array $required)
    {
        try {
            
            foreach ($required as $key) {
                if (!empty($given[$key])) {
                    throw new RequiredParameterFound();
                }
            }
            
            $parameters = implode(', ', $required);
            throw new MissingRequiredParameterException(
                    "At least one parameter required: $parameters"
            );
            
        } catch (RequiredParameterFound $ex) {
            // we have one required parameter, thats all we need
        }
    }
    
    /**
     * Validates if all required parameters are set.
     * 
     * @param array $given
     * @param array $required contains required keys
     */
    static function checkRequiredParametersAreSet(array $given, array $required)
    {
        foreach ($required as $key) {
            if (!isset($given[$key])) {
                throw new MissingRequiredParameterException(
                        "Missing required parameter '$key'"
                );
            }
        }
    }
    
    static function validateValue($given, array $allowed)
    {
        if (!in_array($given, $allowed)) {
            $allowed = implode(', ', $allowed);
            throw new ValueException(
                    "Allowed values: $allowed given value: $given"
            );
        }
    }
}