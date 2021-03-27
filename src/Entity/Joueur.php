<?php

namespace App\Entity;

use App\Entity\Partie;
use App\Entity\User;
use App\Repository\JoueurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JoueurRepository::class)
 */
class Joueur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="Partie")
    * @ORM\JoinColumn(name="partie_id", referencedColumnName="id")
    */
    private $partie;

    /**
    * @ORM\ManyToOne(targetEntity="User")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $prise;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartie(): ?Partie
    {
        return $this->partie;
    }

    public function setPartie(Partie $partie): self
    {
        $this->partie = $partie;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPrises(): ?int
    {
        return $this->user;
    }

    public function setPrises(int $prises): self
    {
        $this->prises = $prises;

        return $this;
    }
}
