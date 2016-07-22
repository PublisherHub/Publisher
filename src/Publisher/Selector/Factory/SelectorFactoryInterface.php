<?php

namespace Publisher\Selector\Factory;

use Publisher\Selector\Parameter\SelectorInterface;
use Publisher\Entry\Exception\SelectorNotFoundException;

interface SelectorFactoryInterface
{
    /**
     * @param string $entryId e.g. 'FacebookPage', 'FacebookUser'
     * @param array $additionalScopes
     * 
     * @throws SelectorNotFoundException
     * 
     * @return SelectorInterface
     */
    public function create(string $entryId, array $additionalScopes = array());
    
}