<?php

namespace App\DataFixtures;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Personne;
//use Faker\Provider\ro_RO\PhoneNumber;

class PersonneFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
	 $faker = Factory::create('fr_FR');
	 for($i=0;$i<100;$i++)
	 {
	 $personne=new Personne();
	 $personne
	 ->setNom($faker->firstNameFemale)
	 ->setPrenom($faker->lastName)
	 ->setPhoneHome('37654321')
	 ->setSexe($faker->numberBetween(1,2))
	 ->setCivilite($faker->numberBetween(1,6))
	 ->setStatutMat($faker->numberBetween(1,6))
	 ->setAdresse($faker->address)
	 ->setPhoneHome($faker->phoneNumber)
	 -> setDateNaissance(new \DateTime())
	 -> setEmail($faker->freeEmail);
	 
	 ;
	 $manager->persist($personne);
	 
	 }
	   $manager->flush();
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
