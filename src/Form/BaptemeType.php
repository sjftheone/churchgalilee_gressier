<?php

namespace App\Form;

use App\Entity\Bapteme;
use App\Entity\Personne;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type;

class BaptemeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('dateBapteme')
            ->add('dateBapteme', DateType::class,['label'=>'Date Baptême(m/j/aa)','widget' => 'single_text','format' => 'yyyy-MM-dd',])
            ->add('lieu', TextType::class, ['label'=>'Lieu','empty_data' => ''])
         //   ->add('lieu')
            ->add('baptiserPar', TextType::class, ['label'=>'Baptisé par','empty_data' => ''])

           /*
            ->add('personne',EntityType::class,
                ['class'=>Personne::class,
                    //'choice_label'=>'nom',
                    'multiple'=>false
                ])
           */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bapteme::class,
        ]);
    }
}
