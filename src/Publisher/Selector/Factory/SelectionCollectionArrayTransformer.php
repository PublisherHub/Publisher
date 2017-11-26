<?php

namespace Publisher\Selector\Factory;

use Publisher\Selector\Factory\SelectionCollectionArrayTransformerInterface;
use Publisher\Selector\Selection\SelectionCollectionInterface;
use Publisher\Selector\Selection\SelectionCollection;
use Publisher\Selector\Selection;

class SelectionCollectionArrayTransformer implements SelectionCollectionArrayTransformerInterface
{
    
    /**
     * @inheritDoc
     */
    public function getSelectionCollectionFromArray(array $collectionData = [])
    {
        $decisions = isset($collectionData['decisions']) ? $collectionData['decisions'] : [];
        $selectionsData = isset($collectionData['selections']) ? $collectionData['selections'] : [];
        
        $selections = [];
        for ($i = 0; $i < count($selectionsData); $i++) {
            $selections[] = new Selection($selectionsData[$i]['name'], $selectionsData[$i]['choices']);
        }
        
        $collection = new SelectionCollection($decisions, $selections);
        
        return $collection;
    }
    
    /**
     * @inheritDoc
     */
    public function getSelectionCollectionAsArray(SelectionCollectionInterface $selectionCollection)
    {
        $selections = $selectionCollection->getSelections();
        
        $selectionsAsArray = [];
        
        for ($i = 0; $i < count($selections); $i++) {
            $selectionsAsArray[] = [
                'name' => $selections[$i]->getName(),
                'choices' => $selections[$i]->getChoices()
            ];
        }
        
        return [
            'decisions' => $selectionCollection->getDecisions(),
            'selections' => $selectionsAsArray
        ];
    }
    
}
