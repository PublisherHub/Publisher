<?php

namespace Publisher\Entry\Factory;

interface EntryFactoryInterface
{
    
    public function getEntry($entryId, array $parameters = array());
}