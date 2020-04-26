<?php

namespace App\Entity;

// use App\Controller\ConversationController;

use PHPUnit\Framework\TestCase;

class ConversationTest extends TestCase
{
    // public function testAdd()
    // {
    //     $calculator = new Calculator();
    //     $result = $calculator->add(30, 12);

    //     // assert that your calculator added the numbers correctly!
    //     $this->assertEquals(42, $result);
    // }

	// public function testTrueAssersToTrue()
	// {
	// 	$this->assertTrue(false);
	// }



    public function testGetContent()
    {
        $conversation = new Conversation();
        $conversation->setContent('ooo');
        $this->assertEquals($conversation->getContent(),'ooo');
    }





}