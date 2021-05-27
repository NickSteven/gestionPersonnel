<?php

namespace App\Entity;

use App\Repository\SoldesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SoldesRepository::class)
 */
class Soldes
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
    private $initial;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $consomme;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $restant;

    /**
     * @ORM\OneToOne(targetEntity=user::class, cascade={"persist", "remove"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInitial(): ?string
    {
        return $this->initial;
    }

    public function setInitial(string $initial): self
    {
        $this->initial = $initial;

        return $this;
    }

    public function getConsomme(): ?string
    {
        return $this->consomme;
    }

    public function setConsomme(string $consomme): self
    {
        $this->consomme = $consomme;

        return $this;
    }

    public function getRestant(): ?string
    {
        return $this->restant;
    }

    public function setRestant(string $restant): self
    {
        $this->restant = $restant;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }
}
