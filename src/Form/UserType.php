<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\CallbackTransformer;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'username',
                'disabled' => false,
                'attr'=>[
                'class'=> 'h-full-width']
            ])
            ->add('fullName', TextType::class, [
                'label' => 'fullname',
            ])
            ->add('email', EmailType::class, [
                'label' => 'email',
            ])

            ->add('Roles', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'choices'  => [
                    'admin' => 'ROLE_ADMIN',
                    'user' => 'ROLE_USER',
                    'membre' => 'ROLE_MEMBRE',
                    'post' => 'ROLE_POST',
                ],
               
            ])
;
/*
        // Data transformer
        $builder->get('Roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));

*/

            /*
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            */
 //       ;





    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
