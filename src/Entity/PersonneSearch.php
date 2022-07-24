<?php
namespace App\Entity;

class PersonneSearch
{
/**
*@var string|null
*/
	private $nom;
/**
*@var string|null
*/
	private $prenom;
/**
*@var string|null
*/
	private $phonePersonnel;
	
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

	 public function getPhonePersonnel(): ?string
    {
        return $this->phonePersonnel;
    }

    public function setPhonePersonnel(string $phonePersonnel): self
    {
        $this->phonePersonnel = $phonePersonnel;

        return $this;
    }


	
	
}