<?php

namespace Publisher\Mode;

use Publisher\Helper\BaseEntryHelperInterface;

/**
 * 
 */
class ContentTransformer
{
    
    /**
     * @var BaseEntryHelperInterface
     */
    protected $entryHelper;
    
    /**
     * @param BaseEntryHelperInterface $entryHelper
     */
    public function __construct(BaseEntryHelperInterface $entryHelper)
    {
        $this->entryHelper = $entryHelper;
    }
    
    /**
     * Transforms the mode specific data for each entry
     * to the mapped entry data.
     * 
     * @param string $mode
     * @param array $modeData
     * 
     * @return array
     */
    public function transform(string $mode, array $modeData)
    {
        $return = array();
        
        foreach($modeData as $data) {
            
            $modeClass = $this->entryHelper->getModeClass($mode, $data['entry']);
            $entity = new $modeClass($data['content']);
            
            $entryData = array();
            $entryData['entry'] = $data['entry'];
            $entryData['content'] = $entity->generateBody();
            /* Since the publisher manager ask for the parameter data
             * sooner or later we'll provide the key here
             * if its still missing.
             */
            $entryData['parameters'] = isset($data['parameters']) ? $data['parameters'] : array();
            
            $return[] = $entryData;
        }
        
        return $return;
    }
    
}