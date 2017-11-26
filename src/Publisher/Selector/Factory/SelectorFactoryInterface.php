<?php

namespace Publisher\Selector\Factory;

use Publisher\Selector\Parameter\SelectorInterface;
use Publisher\Selector\Selection\SelectionCollectionInterface;
use Publisher\Entry\Exception\SelectorNotFoundException;

interface SelectorFactoryInterface
{
    /**
     * @param string                            $entryId e.g. 'FacebookPage', 'FacebookUser'
     * @param SelectionCollectionInterface|null $selectionCollection
     * 
     * @throws SelectorNotFoundException
     * 
     * @return SelectorInterface
     */
    public function getSelector(
        string $entryId,
        SelectionCollectionInterface $selectionCollection = null
    );
    
}