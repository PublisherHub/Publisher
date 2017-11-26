<?php

namespace Publisher\Selector\Manager;

use Publisher\Selector\Selection\SelectionCollectionInterface;

interface SelectorManagerInterface
{
    
    /**
     * Creates a Selector for each $entryIds that will be used afterwards
     * and initializes the Selectors SelectionCollections
     * based on the data given in $collectionsData.
     * 
     * The given $collectionsData should be compatible
     * with the output of the method 'getCollectionsAsArray'.
     * 
     * Example:
     * $entryIds = ['ServiceUser', 'ProviderPage'];
     * $collectionsData = [
     *     'ProviderPage' => $selectionCollectionAsArray
     * ];
     * 
     * @param string[]                       $entryIds
     * @param SelectionCollectionInterface[] $collectionsData
     * 
     * @return void
     */
    public function setupSelectors(array $entryIds, array $collectionsData);
    
    /**
     * Update each selector with $choices['EntryrId'].
     * 
     * Example:
     * $choices = [
     *  'ServiceUser' => [], // e.g. no choices for ServiceUserEntry
     *  'ServicePage' => [
     *      ['firstSelection' => '1', 'secondSelection' => 'b')
     *  ],
     *  'ServiceGroup' => [
     *      ['firstSelection' => 'f')
     *  ]
     * ];
     * 
     * @param array $choices
     * 
     * @return void
     */
    public function updateSelectors(array $choices);
    
    /**
     * Returns whether or not the selectors collected all required parameters. 
     * 
     * @returns bool
     */
    public function areAllParametersSet();
    
    /**
     * Executes the current requests for each selector.
     * 
     * @return void
     */
    public function executeCurrentSteps();
    
    /**
     * Returns the current SelectionCollections for each selector.
     * 
     * Example:
     * $collections = [
     *  'ServicePage' => $servicePageSelectionCollection,
     *  'ServiceGroup' => $serviceGroupSelectionCollection
     * ];
     * 
     * @return array
     */
    public function getCollections();
    
    /**
     * Returns the current SelectionCollections as data arrays for each selector.
     * The output should be compatible with the second argument
     * of the method 'setupSelectors'.
     * 
     * Example:
     * $collections = [
     *  'ServicePage' => $servicePageSelectionCollectionAsArray,
     *  'ServiceGroup' => $serviceGroupSelectionCollectionAsArray
     * ];
     * 
     * @return array
     * @return array
     */
    public function getCollectionsAsArray();
    
    /**
     * Returns the parameters of all selectors.
     * 
     * Example:
     * $parameters = [
     *  'ServiceUser' => [], // no parameters for ServiceUserEntry
     *  'ServicePage' => [
     *      'firstParameter' => '1', 'secondParameter' => 'b'
     *  ],
     *  'ServiceGroup' => [
     *      'firstParameter' => 'f'
     *  ]
     * ];
     * 
     * @return array
     */
    public function getParameters();
    
}

