<?php

namespace Publisher\Selector\Manager;

interface SelectorManagerInterface
{
    
    /**
     * Update each selector with $choices['selector'].
     * 
     * Example:
     * $choices = array(
     *  'ServiceUser' => array(), // e.g. no choices for ServiceUserEntry
     *  'ServicePage' => array(
     *      array('firstSelection' => '1', 'secondSelection' => 'b')
     *  ),
     *  'ServiceGroup' => array(
     *      array('firstSelection' => 'f')
     *  )
     * );
     * 
     * @param array $choices
     * 
     * @return void
     */
    public function updateSelectors(array $choices);
    
    /**
     * Returns the current selections of each selector.
     * 
     * Example:
     * $selections = array(
     *  'ServiceUser' => array(), // e.g. no selections for ServiceUserEntry
     *  'ServicePage' => array($firstSelection1, $secondSelection),
     *  'ServiceGroup' => array($firstSelection1)
     * );
     * 
     * @return array contains arrays of instances of \Publisher\Selector\Selection
     */
    public function getSelections();
    
    /**
     * Returns the current selections of each selector.
     * 
     * Example:
     * $selections = array(
     *  'ServiceUser' => array(), // e.g. no selections for ServiceUserEntry
     *  'ServicePage' => array(
     *      array('firstSelection' => array('1'=>'a','2'=>'b'),
     *      array('secondSelection' => array('a'=>'1','b'=>'2')
     *  ),
     *  'ServiceGroup' => array(
     *      array('firstSelection' => array('f'=>'foo','b'=>'bar')
     *  )
     * );
     * 
     * @return array
     */
    public function getSelectionsAsArray();
    
}

