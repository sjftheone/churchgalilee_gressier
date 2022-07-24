<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \Date
     * @ORM\Column(type="date",nullable=false)
     *  @Assert\NotBlank(
     * message = "Veuillez remplir le champ date d'evenement"
     *)
     */
    private $eventDate;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $eventType;

    /**
     *@ORM\Column(type="string", length=255,nullable=true)
     */
    private $eventLieu;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $eventInfos;

    /**
     * @ORM\ManyToOne(targetEntity=Personne::class, inversedBy="evenements")
     */
    private $personnes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): self
    {
        $this->eventDate = $eventDate;
        return $this;
    }

    public function getEventType(): ?string
    {
        return $this->eventType;
    }

    public function setEventType(string $eventType): self
    {
        $this->eventType = $eventType;

        return $this;
    }

    public function getEventLieu(): ?string
    {
        return $this->eventLieu;
    }

    public function setEventLieu(string $eventLieu): self
    {
        $this->eventLieu = $eventLieu;

        return $this;
    }

    public function getEventInfos(): ?string
    {
        return $this->eventInfos;
    }

    public function setEventInfos(string $eventInfos): self
    {
        $this->eventInfos = $eventInfos;

        return $this;
    }

    public function getPersonnes(): ?Personne
    {
        return $this->personnes;
    }

    public function setPersonnes(?Personne $personnes): self
    {
        $this->personnes = $personnes;

        return $this;
    }
}
