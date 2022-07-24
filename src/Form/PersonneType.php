<?php 
// src/Form/PersonneType.php
namespace App\Form;

use App\Entity\Bapteme;
use App\Entity\Personne;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;




class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sexe', ChoiceType::class,[
			'choices'=>$this->getSexes(),
			'label' => 'Sexe',])
			->add('civilite', ChoiceType::class,['choices'=>$this->getCivilites(),'label' => 'Civilité'])
            ->add('nom', TextType::class,['label'=>'Nom','empty_data' => ''])
			->add('prenom', TextType::class,['label'=>'Prénom','empty_data' => ''])
			->add('dateNaissance', DateType::class,['label'=>'Date de Naissance','widget' => 'single_text','format' => 'yyyy-MM-dd','empty_data' => '','by_reference' => true])
			
			->add('lieuNaissance', TextType::class, ['label'=>'Lieu de Naissance'])
			->add('nationalite', TextType::class,['label'=>'Nationalité'])
			->add('profession', TextType::class,['label'=>'Profession'])
			->add('statutMat', ChoiceType::class,['choices'=>$this->getStatutMats(),'label'=>'Statut Matrimonial'])
			->add('adresse', TextType::class,['label'=>'Adresse','empty_data' => ''])
			->add('codePostal', TextType::class,['label'=>'Code Postal'])
			->add('ville', TextType::class, ['label'=>'Ville'])
			->add('phoneHome', TextType::class,['label'=>'Phone domicile'])
			->add('phonePersonnel', TextType::class,['label'=>'Phone personnel','empty_data' => ''])
			->add('phoneTravail', TextType::class,['label'=>'Phone travail'])
			->add('email', EmailType::class, ['label'=>'Email'])
		    ->add('infosAdd', TextAreaType::class, ['label'=>'Infos additionnelles'])
			->add('imageFile',FileType::class,['label'=>'Photo'],['required'=>false]);

/*
            ->add('baptemes',EntityType::class,
                ['class'=>Bapteme::class,
                'choice_label'=>'baptiserPar',
                'multiple'=>true
                ]);
*/
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
	private function getSexes()
	{
		$choices= Personne::SEXE;
		$output=[];
		foreach($choices as $k=>$v)
		{
			$output[$v]=$k;
		}
		return $output;
	}
	
	
	private function getCivilites()
	{
		$choices= Personne::CIVILITE;
		$output=[];
		foreach($choices as $k=>$v)
		{
			$output[$v]=$k;
		}
		return $output;
	}
	
	private function getStatutMats()
	{
		$choices= Personne::STATUTMAT;
		$output=[];
		foreach($choices as $k=>$v)
		{
			$output[$v]=$k;
		}
		return $output;
	}
	
	
	
}