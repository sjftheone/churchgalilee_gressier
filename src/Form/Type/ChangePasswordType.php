<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Defines the custom form field type used to change user's password.
 *
 * @author Romain Monteil <monteil.romain@gmail.com>
 */
class ChangePasswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'constraints' => [
                    new UserPassword(),
                ],
                'label' => 'current password',
                'attr' => [
                    'autocomplete' => 'off',
                ],
            ])
            ->add('newPassword', RepeatedType::class, [
                'label' => false,
                'type' => PasswordType::class,
                
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 5,
                        'max' => 128,
                    ]),
                ],
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control trapezoid',
                        'placeholder' => 'New Password'
                    ],
                    'row_attr' => [
                        'class' => 'col-10', 
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control trapezoid',
                        'placeholder' => 'repeat Password'
                    ],
                    'row_attr' => [
                        'class' => 'col-10', 
                    ]
                ],
            ])
        ;
    }
}
