<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\File\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('Name', TextType::class)
        ->add('Category', ChoiceType::class, [
            'choices'  => [
                'Mobile' => 'mobile',
                'Laptop' => 'laptop',
            ],
        ])
        ->add('Price')
        ->add('Quantity')
        ->add('Short_Description', TextareaType::class)
        ->add('Long_Description', TextareaType::class)
        ->add('Small_img', FileType::class)
        ->add('Large_img', FileType::class)
        ->add('save', SubmitType::class, ['label' => 'Submit'])
        ;

        $builder->get('Small_img')
            ->addModelTransformer(new CallbackTransformer(
                function ($smallImgAsString) {
                    if ($smallImgAsString) {
                        return new File(__DIR__ . '/../../public/' . $smallImgAsString);
                    }
                },
                function ($smallImgAsFile) {
                    $smallImgAsFile->move(__DIR__ . '/../../public/images/small', $smallImgAsFile->getClientOriginalName());
                    return ('images/small/' . $smallImgAsFile->getClientOriginalName());
                }
            ))
        ;

        $builder->get('Large_img')
            ->addModelTransformer(new CallbackTransformer(
                function ($largeImgAsString) {
                    // transform the string back to an array
                    if ($largeImgAsString) {
                        return new File(__DIR__ . '/../../public/' . $largeImgAsString);
                    }
                },
                function ($largeImgAsFile) {
                    $largeImgAsFile->move(__DIR__ . '/../../public/images/large', $largeImgAsFile->getClientOriginalName());
                    return ('images/large/' . $largeImgAsFile->getClientOriginalName());
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
