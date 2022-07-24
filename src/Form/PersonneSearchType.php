<?php

namespace App\Form;

use App\Entity\PersonneSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class PersonneSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,
			['required'=>false,
			'label'=>false,
			'attr'=>[
			'placeholder'=>'Nom'
			]
		])
          ->add('prenom',TextType::class,
			['required'=>false,
			'label'=>false,
			'attr'=>[
			'placeholder'=>'Prénom'
			]
		])
          ->add('phonePersonnel',TextType::class,
			['required'=>false,
			'label'=>false,
			'attr'=>[
			'placeholder'=>'Phone Personnel'
			]
		])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonneSearch::class,
			'method'=>'get',
			'csrf_protection'=>false
        ]);
    }
	public function getBlockPrefix()
	{
		return "";
	}
}
