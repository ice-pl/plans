<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;








    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserProject", mappedBy="userMap", cascade={"persist", "remove"})
     */
    protected $userInverse;













    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Conversation", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $conversations;







    public function __construct()
    {
        $this->conversations = new ArrayCollection();
        $this->userInverse = new ArrayCollection();
    }






    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }









    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->setUser($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->contains($conversation)) {
            $this->conversations->removeElement($conversation);
            // set the owning side to null (unless already changed)
            if ($conversation->getUser() === $this) {
                $conversation->setUser(null);
            }
        }

        return $this;
    }














    /**
     * @return Collection|UserProject[]
     */
    public function getUserInverse(): Collection
    {
        return $this->userInverse;
    }

    public function addUserInverse(UserProject $userInverse): self
    {
        if (!$this->userInverse->contains($userInverse)) {
            $this->userInverse[] = $userInverse;
            $userInverse->setUserMap($this);
        }

        return $this;
    }

    public function removeUserInverse(UserProject $userInverse): self
    {
        if ($this->userInverse->contains($userInverse)) {
            $this->userInverse->removeElement($userInverse);
            // set the owning side to null (unless already changed)
            if ($userInverse->getUserMap() === $this) {
                $userInverse->setUserMap(null);
            }
        }

        return $this;
    }



































}
