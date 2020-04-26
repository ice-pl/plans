<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SampleItemRepository")
 */
class SampleItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;


    /**
     * @ORM\Column(type="string", length=999, nullable=true)
     */
    private $description;


    /**
     * @ORM\Column(type="integer")
     */
    private $value;






    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SampleProduct", inversedBy="sample_items")
     */
    private $sample_product;



    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $interval_counted;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $delay_counted;








    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getSampleProduct(): ?SampleProduct
    {
        return $this->sample_product;
    }

    public function setSampleProduct(?SampleProduct $sample_product): self
    {
        $this->sample_product = $sample_product;

        return $this;
    }



    public function getIntervalCounted(): ?int
    {
        return $this->interval_counted;
    }

    public function setIntervalCounted(?int $interval_counted): self
    {
        $this->interval_counted = $interval_counted;

        return $this;
    }

    public function getDelayCounted(): ?int
    {
        return $this->delay_counted;
    }

    public function setDelayCounted(?int $delay_counted): self
    {
        $this->delay_counted = $delay_counted;

        return $this;
    }


}
