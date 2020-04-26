<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserProjectRepository")
 */
class UserProject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

 


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userInverse")
     */
    private $userMap;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="projectInverse")
     */
    private $projectMap;






    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserMap(): ?User
    {
        return $this->userMap;
    }

    public function setUserMap(?User $userMap): self
    {
        $this->userMap = $userMap;

        return $this;
    }

    public function getProjectMap(): ?Project
    {
        return $this->projectMap;
    }

    public function setProjectMap(?Project $projectMap): self
    {
        $this->projectMap = $projectMap;

        return $this;
    }
}
