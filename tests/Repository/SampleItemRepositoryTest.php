<?php

namespace App\Tests\Repository;

use App\Entity\SampleItem;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;




class SampleItemRepositoryTest extends KernelTestCase
{


    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }



    public function testWhatIsInDescription()
    {
        $description_in_array = $this->entityManager
            ->getRepository(SampleItem::class)
            ->findSampleItemDescription_byId(3)
        ;
        $this->assertSame([0 =>['description' => 'i3']], $description_in_array);
    }





    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }




}