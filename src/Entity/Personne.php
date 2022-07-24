<?php

// src/Entity/Personne.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function Symfony\Component\String\u;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



/**
 * @ORM\Entity
 * @ORM\Table(name="Personne")
 * @Vich\Uploadable
 * Defines the properties of the Comment entity to represent the blog comments.
 * See https://symfony.com/doc/current/doctrine.html#creating-an-entity-class
 *
 * Tip: if you have an existing database, you can generate these entity class automatically.
 * See https://symfony.com/doc/current/doctrine/reverse_engineering.html
 *@ORM\Entity(repositoryClass="App\Repository\PersonneRepository")
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class Personne
{
 public function __construct()
{
   // $this->dateNaissance = new \DateTime();
    $this->baptemes = new ArrayCollection();
    $this->integrations = new ArrayCollection();
    $this->evenements = new ArrayCollection();
    $this->publishedAt = new \DateTime('');
}
	
	const SEXE=[2=>'',0=>'M',1=>'F'];
	const CIVILITE=[1=>'M',2=>'MME',3=>'MLLE',4=>'FR',5=>'SR',6=>'PAST'];
	const STATUTMAT=[1=>'Célibataire',2=>'Marié(e)',3=>'veuf(ve)',4=>'divorcé',5=>'séparé',6=>'autre'];
	const MOTIF_ENTREE=[1=>'baptême ',2=>'transfert',3=>'profession de foi',4=>'autre'];
	
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
	
	/**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string
     */
    private $imageName;
	
	 /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="property_image", fileNameProperty="imageName")
     * @Assert\Image(mimeTypes="image/jpeg",mimeTypesMessage="format photo invalide")
     * @var File|null
     */
    private $imageFile;
	  /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le champ Sexe doit être renseigné")
     * 
     */
    private $sexe;
	
	  /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank
	 *
     */
    private $civilite;
	
	  /**
     * @var string
     * @ORM\Column(type="string")
	 *
     * 
	 * @Assert\Length(
     * min = 3,
     * max = 250,
     * minMessage = "Ce nom est trop court",
     * maxMessage = "Ce nom est trop long"
     *)
	 */
    private $nom;
	
/*
    public function __toString()
    {
        return $this->getNom();
    }
*/


	  /**
     * @var string
     *
     * @ORM\Column(type="string")
	 * @Assert\Length(
     * min = 3,
     * max = 250,
     * minMessage = "Ce prénom est trop court",
     * maxMessage = "Ce prénom est trop long"
	 *)
     */
    private $prenom;	
	 /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime",nullable=true)
	 * @Assert\NotBlank(
     * message = "Veuillez remplir le champ date de naissance"
	 *)
     */
    private $dateNaissance;
	
	 /**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     * 
     */
    private $lieuNaissance;
	
	
	/**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     */
    private $nationalite;
	
	
	/**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     */
    private $profession;
	
	
	/**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     */
    private $statutMat;
	
	/**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     * @Assert\NotBlank(
     * message = "Veuillez remplir le champ adresse"
	 *)
     */
    private $adresse;
	
	/**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     */
    private $codePostal;
	
	/**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     */
    private $ville;
	
	/**
     * @var string
     * @ORM\Column(type="string",nullable=true)
     */
    private $phoneHome;
	
	/**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     *  @Assert\NotBlank(
     * message = "Veuillez remplir le champ phone personnel"
     *)
     */
    private $phonePersonnel;
	
	/**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     */
    private $phoneTravail;
	
	/**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     */
    private $email;
	
	/**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     */
    private $infosAdd;
	
	  /**
     * @ORM\Column(type="datetime",nullable=true)
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime",nullable=true)
	 * @Assert\NotBlank
	 *
     */
    private $publishedAt;

  

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Bapteme::class, mappedBy="personne",orphanRemoval=true)
     */
    private $baptemes;

    /**
     * @ORM\OneToMany(targetEntity=Integration::class, mappedBy="personne",orphanRemoval=true)
     */
    private $integrations;

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="personnes",orphanRemoval=true)
     */
    private $evenements;

    public function getId(): ?int
    {
        return $this->id;
    }
	

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getLieuNaissance(): ?string
    {
        return $this->lieuNaissance;
    }

    public function setLieuNaissance(string $lieuNaissance): self
    {
        $this->lieuNaissance = $lieuNaissance;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(string $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getStatutMat(): ?string
    {
        return $this->statutMat;
    }

    public function setStatutMat(string $statutMat): self
    {
        $this->statutMat = $statutMat;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPhoneHome(): ?string
    {
        return $this->phoneHome;
    }

    public function setPhoneHome(string $phoneHome): self
    {
        $this->phoneHome = $phoneHome;

        return $this;
    }

    public function getPhonePersonnel(): ?string
    {
        return $this->phonePersonnel;
    }

    public function setPhonePersonnel(string $phonePersonnel): self
    {
        $this->phonePersonnel = $phonePersonnel;

        return $this;
    }

    public function getPhoneTravail(): ?string
    {
        return $this->phoneTravail;
    }

    public function setPhoneTravail(string $phoneTravail): self
    {
        $this->phoneTravail = $phoneTravail;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getInfosAdd(): ?string
    {
        return $this->infosAdd;
    }

    public function setInfosAdd(string $infosAdd): self
    {
        $this->infosAdd = $infosAdd;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }
	
	 /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
/*		
		 if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }
*/

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }

    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Bapteme[]
     */
    public function getBaptemes(): Collection
    {
        return $this->baptemes;
    }

    public function addBapteme(Bapteme $bapteme): self
    {
        if (!$this->baptemes->contains($bapteme)) {
            $this->baptemes[] = $bapteme;
            $bapteme->setPersonne($this);
        }

        return $this;
    }

    public function removeBapteme(Bapteme $bapteme): self
    {
        if ($this->baptemes->removeElement($bapteme)) {
            // set the owning side to null (unless already changed)
            if ($bapteme->getPersonne() === $this) {
                $bapteme->setPersonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Integration[]
     */
    public function getIntegrations(): Collection
    {
        return $this->integrations;
    }

    public function addIntegration(Integration $integration): self
    {
        if (!$this->integrations->contains($integration)) {
            $this->integrations[] = $integration;
            $integration->setPersonne($this);
        }

        return $this;
    }

    public function removeIntegration(Integration $integration): self
    {
        if ($this->integrations->removeElement($integration)) {
            // set the owning side to null (unless already changed)
            if ($integration->getPersonne() === $this) {
                $integration->setPersonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->setPersonnes($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getPersonnes() === $this) {
                $evenement->setPersonnes(null);
            }
        }

        return $this;
    }


	
	

    /**
     * Get the value of publishedAt
     *
     * @return  \DateTime
     */ 
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set the value of publishedAt
     *
     * @param  \DateTime  $publishedAt
     *
     * @return  self
     */ 
    public function setPublishedAt(\DateTime $publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get the value of author
     *
     * @return  User
     */ 
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @param  User  $author
     *
     * @return  self
     */ 
    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }
}
