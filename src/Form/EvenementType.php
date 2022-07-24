<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eventDate',DateType::class,['label'=>'date événement','widget' => 'single_text','format' => 'yyyy-MM-dd','empty_data' => '','by_reference' => true])
            ->add('eventType',TextType::class, ['label'=>'Type événement'])
            ->add('eventLieu',TextType::class, ['label'=>'Lieu'])
            ->add('eventInfos',TextType::class, ['label'=>'infos addit'])
            //->add('personnes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
