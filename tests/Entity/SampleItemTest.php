<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;



class SampleItemTest extends TestCase
{




    public function testSetAndGetDescription()
    {
        $sampleItem = new SampleItem();
        $description = 'nowy opis';
        $sampleItem->setDescription($description);
        $result = $sampleItem->getDescription();

        $this->assertEquals('nowy opis', $result);
    }








}