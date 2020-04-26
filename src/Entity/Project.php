<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;




/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;




    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="project", cascade={"persist", "remove"})
     */
    protected $products;







    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserProject", mappedBy="projectMap", cascade={"persist", "remove"})
     */
    protected $projectInverse;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $owner;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $share_questions = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $share_answers = [];






















    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->projectInverse = new ArrayCollection();
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

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setProject($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getProject() === $this) {
                $product->setProject(null);
            }
        }

        return $this;
    }















    /**
     * @return Collection|UserProject[]
     */
    public function getProjectInverse(): Collection
    {
        return $this->projectInverse;
    }

    public function addProjectInverse(UserProject $projectInverse): self
    {
        if (!$this->projectInverse->contains($projectInverse)) {
            $this->projectInverse[] = $projectInverse;
            $projectInverse->setProjectMap($this);
        }

        return $this;
    }

    public function removeProjectInverse(UserProject $projectInverse): self
    {
        if ($this->projectInverse->contains($projectInverse)) {
            $this->projectInverse->removeElement($projectInverse);
            // set the owning side to null (unless already changed)
            if ($projectInverse->getProjectMap() === $this) {
                $projectInverse->setProjectMap(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?int
    {
        return $this->owner;
    }

    public function setOwner(?int $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getShareQuestions(): ?array
    {
        return $this->share_questions;
    }

    public function setShareQuestions(?array $share_questions): self
    {
        $this->share_questions = $share_questions;

        return $this;
    }

    public function getShareAnswers(): ?array
    {
        return $this->share_answers;
    }

    public function setShareAnswers(?array $share_answers): self
    {
        $this->share_answers = $share_answers;

        return $this;
    }








































}
