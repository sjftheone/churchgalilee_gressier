<?php

namespace App\Form;

use App\Entity\Integration;
use App\Entity\Personne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class IntegrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateIn',DateType::class,['label'=>'date d/entrée','widget' => 'single_text','format' => 'yyyy-MM-dd','empty_data' => '','by_reference' => true])
            
            ->add('infosIn', ChoiceType::class,['choices'=>$this->getMotif_Entree(),'label'=>'Motif d/entrée','empty_data' => ''])
            // ->add('dateOut')
           ->add('dateOut',DateType::class,['label'=>'date de sortie','widget' => 'single_text','empty_data' => '','format' => 'yyyy-MM-dd','required' => false,])
           ->add('infosOut', TextType::class, ['label'=>'Raison de sortie'])
          //  ->add('personne')
        ;
    }
//->add('dateBapteme', DateType::class,['label'=>'date Bapteme','widget' => 'single_text','format' => 'yyyy-MM-dd',])

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Integration::class,
        ]);
    }


    private function getMotif_Entree()
	{
		$choices= Personne::MOTIF_ENTREE;
		$output=[];
		foreach($choices as $k=>$v)
		{
			$output[$v]=$k;
		}
		return $output;
	}


}
