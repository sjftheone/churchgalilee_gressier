<?php

namespace App\Entity;

use App\Repository\BaptemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BaptemeRepository::class)
 */
class Bapteme
{

    public function __construct()
    {
        $this->dateBapteme = new \DateTime();

    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * 
     * 
     */
    private $dateBapteme;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     * min = 3,
     * max = 250,
     * minMessage = "Ce lieu est trop court",
     * maxMessage = "Ce lieu est trop long"
     * )
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Personne::class, inversedBy="baptemes")
     */
    private $personne;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $baptiserPar;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateBapteme(): ?\DateTimeInterface
    {
        return $this->dateBapteme;
    }

    public function setDateBapteme(\DateTimeInterface $dateBapteme): self
    {
        $this->dateBapteme = $dateBapteme;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getPersonne(): ?Personne
    {
        return $this->personne;
    }

    public function setPersonne(?Personne $personne): self
    {
        $this->personne = $personne;

        return $this;
    }

    public function getBaptiserPar(): ?string
    {
        return $this->baptiserPar;
    }

    public function setBaptiserPar(string $baptiserPar): self
    {
        $this->baptiserPar = $baptiserPar;

        return $this;
    }
}
