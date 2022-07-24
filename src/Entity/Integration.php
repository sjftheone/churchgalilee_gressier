<?php

namespace App\Entity;

use App\Repository\IntegrationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass=IntegrationRepository::class)
 * @ORM\Table(name="Integration")
 */
class Integration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *  @var \Date
     * @ORM\Column(type="date",nullable=false)
     *  @Assert\NotBlank(
     * message = "Veuillez remplir le champ date d'entree"
     *)
     */
    private $dateIn;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(
     * message = "Veuillez remplir le champ motif d'entree"
     *)
     */
    private $infosIn;

    /**
     * @var \Date
     * @ORM\Column(type="date",nullable=true)
     *
     */
    private $dateOut;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $infosOut;

    /**
     * @ORM\ManyToOne(targetEntity=Personne::class, inversedBy="integrations")
     */
    private $personne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateIn(): ?\DateTimeInterface
    {
        return $this->dateIn;
    }

    public function setDateIn(\DateTimeInterface $dateIn): self
    {
        $this->dateIn = $dateIn;

        return $this;
    }

    public function getInfosIn(): ?string
    {
        return $this->infosIn;
    }

    public function setInfosIn(string $infosIn): self
    {
        $this->infosIn = $infosIn;
        return $this;

    }

    public function getDateOut(): ?\DateTimeInterface
    {
        return $this->dateOut;
    }

    public function setDateOut(?\DateTimeInterface $dateOut): self
    {
        $this->dateOut = $dateOut;
        return $this;
    }

    public function getInfosOut(): ?string
    {
        return $this->infosOut;
    }

    public function setInfosOut(?string $infosOut): self
    {
        $this->infosOut = $infosOut;

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
}
