<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire minimum 8 caractères")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Veuillez taper le même mot de passe")
     */

    public $confirm_password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Conges::class, mappedBy="users", orphanRemoval=true)
     */
    private $conges;

    public function __construct()
    {
        $this->conges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials() {}

    public function getSalt() {}

    public function getRoles(): array {
        // Chaque utilisateur doit au moins avoir une rôle
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Conges[]
     */
    public function getConges(): Collection
    {
        return $this->conges;
    }

    public function addConge(Conges $conge): self
    {
        if (!$this->conges->contains($conge)) {
            $this->conges[] = $conge;
            $conge->setUsers($this);
        }

        return $this;
    }

    public function removeConge(Conges $conge): self
    {
        if ($this->conges->removeElement($conge)) {
            // set the owning side to null (unless already changed)
            if ($conge->getUsers() === $this) {
                $conge->setUsers(null);
            }
        }

        return $this;
    }
}
