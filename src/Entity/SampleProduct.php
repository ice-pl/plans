<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SampleProductRepository")
 */
class SampleProduct
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
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $image;




    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SampleItem", mappedBy="sample_product", cascade={"persist"})
     */
    protected $sample_items;

    public function __construct()
    {
        $this->sample_items = new ArrayCollection();
    }








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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|SampleItem[]
     */
    public function getSampleItems(): Collection
    {
        return $this->sample_items;
    }

    public function addSampleItem(SampleItem $sampleItem): self
    {
        if (!$this->sample_items->contains($sampleItem)) {
            $this->sample_items[] = $sampleItem;
            $sampleItem->setSampleProduct($this);
        }

        return $this;
    }

    public function removeSampleItem(SampleItem $sampleItem): self
    {
        if ($this->sample_items->contains($sampleItem)) {
            $this->sample_items->removeElement($sampleItem);
            // set the owning side to null (unless already changed)
            if ($sampleItem->getSampleProduct() === $this) {
                $sampleItem->setSampleProduct(null);
            }
        }

        return $this;
    }
}
