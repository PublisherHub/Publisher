<?php

namespace Publisher\Selector\Factory;

use Publisher\Selector\Selection\SelectionCollectionInterface;

/**
 * An interface with methods to retrieve a SelectionCollection based on array data
 * and rebuild the data of a SelectionCollection as an array.
 */
interface SelectionCollectionArrayTransformerInterface
{
    
    /**
     * Example:
     * $collectionData = [
     *     'decisions' => ['param1' => 'foo', 'param2' => 'bar'],
     *     'selections' => [
     *         0 => [
     *             'name' => 'param1',
     *             'choices' => ['option1' => 'optionValue1', ...]
     *         ],
     *         1 => [
     *             'name' => 'param2',
     *             'choices' => ['option1' => 'optionValue1', ...]
     *         ]
     *     ]
     * ]
     * 
     * @param array $collectionData
     * 
     * @return SelectionCollectionInterface
     */
    public function getSelectionCollectionFromArray(array $collectionData = []);
    
    /**
     * @param SelectionCollectionInterface $selectionCollection
     * 
     * @return array
     */
    public function getSelectionCollectionAsArray(SelectionCollectionInterface $selectionCollection);
    
}
