<?php

namespace Unit\Publisher\Selector\Factory;

use PHPUnit\Framework\TestCase;
use Publisher\Selector\Factory\SelectionCollectionArrayTransformer;
use Publisher\Selector\Selection\SelectionCollection;
use Publisher\Selector\Selection;

class SelectionCollectionArrayTransformerTest extends TestCase
{
    
    public function testArrayTransformation()
    {
        $selections = [];
        for ($i = 0; $i < 3; $i++) {
            $selections[] = new Selection("param$1", [
                "option1$i" => "value1$i",
                "option2$i" => "value2$i",
                "option3$i" => "value3$i",
            ]);
        }
        $decisions = ['param1' => 'value21', 'param2' => 'value32'];
        
        $selectionCollection = new SelectionCollection($decisions, $selections);
        
        $transformer = new SelectionCollectionArrayTransformer();
        $selectionCollectionAsArray = $transformer->getSelectionCollectionAsArray($selectionCollection);
        $this->assertArrayHasKey('decisions', $selectionCollectionAsArray);
        $this->assertArrayHasKey('selections', $selectionCollectionAsArray);
        for ($i = 0; $i < count($i); $i++) {
            $this->assertArrayHasKey('name', $selectionCollectionAsArray['selections'][$i]);
            $this->assertTrue(is_array($selectionCollectionAsArray['selections'][$i]['choices']));
        }
        
        $recoveredSelectionCollection = $transformer->getSelectionCollectionFromArray($selectionCollectionAsArray);
        $this->assertEquals($recoveredSelectionCollection, $selectionCollection);
    }
    
}
