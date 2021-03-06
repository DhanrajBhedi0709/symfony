<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class RegistrationFormType
 * @package App\Form
 */
class RegistrationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                'attr' => ['class' => 'form-control'],
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'password',
                RepeatedType::class,
                [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',

                'constraints' => [
                    new NotBlank(
                        [
                        'message' => 'Please enter a password',
                        ]
                    ),
                    new Length(
                        [
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                        ]
                    ),
                ],
                'required' => true,
                'first_options'  => ['label' => 'Password', 'attr' => ['class' => 'form-control']],
                'second_options' => ['label' => 'Repeat Password', 'attr' => ['class' => 'form-control']],
                ]
            )
            ->add(
                'profileImage',
                FileType::class,
                [
                    'label' => 'Profile Image',
                    'mapped' => false,
                    'required' => true,
                    'constraints' => [
                        new File(
                            [
                                'maxSize' => '2048k',
                                'mimeTypes' => [
                                    'image/jpg',
                                    'image/jpeg',
                                    'image/png',
                                ],
                                'mimeTypesMessage' => 'Please upload a valid jpg, jpeg, png Image',
                            ]
                        )
                    ],
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => User::class,
            ]
        );
    }
}
