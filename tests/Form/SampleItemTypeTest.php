<?php

namespace App\Tests\Form;

use App\Form\SampleItemType;

// use App\Model\TestObject;
use App\Entity\SampleItem;


use Symfony\Component\Form\Test\TypeTestCase;

class SampleItemTypeTest extends TypeTestCase
{


	public function testSubmitValidData()
	{
		$formData = [
			'name' => 'test',
			'position' => '2',
			'description' => 'test2',
			'value' => '3',
		];


		// $formData = [];


		$objectToCompare = new SampleItem();


		// $objectToCompare = new TestObject();
    // $objectToCompare will retrieve data from the form submission; pass it as the second argument
		$form = $this->factory->create(SampleItemType::class, $objectToCompare);

		$object = new SampleItem();

		$object->setName('test');
		$object->setPosition('2');
		$object->setDescription('test2');
		$object->setValue('3');

		// $object = new TestObject();
    // ...populate $object properties with the data stored in $formData

    // submit the data to the form directly
		$form->submit($formData);

		$this->assertTrue($form->isSynchronized());

    // check that $objectToCompare was modified as expected when the form was submitted
		$this->assertEquals($object, $objectToCompare);

		$view = $form->createView();
		$children = $view->children;

		foreach (array_keys($formData) as $key) {
			$this->assertArrayHasKey($key, $children);
		}
	}




	// public function testSubmit()
	// {
	// 	$formData = array(); // Should have info to fill the form with
	// 	$objForm = new SampleItem();
	// 	$form = $this->factory->create(SampleItemType::class, $objForm);
	// 	$obj = new SampleItem();
	// 	//...populating object...
	// 	$form->submit($formData);
	// 	$this->assertTrue($form->isSynchronized());
	// 	$this->assertEquals($obj, $objForm);
	// 	// Check FormView
	// 	$view = $form->createView();
	// 	$children = $view->children;
	// 	foreach (array_keys($formData) as $key) {
	// 		$this->assertArrayHasKey($key, $children);
	// 	}
	// }


}