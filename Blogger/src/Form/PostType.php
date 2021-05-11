<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class PostType
 * @package App\Form
 */
class PostType extends AbstractType
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    /**
     * PostType constructor.
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                'attr' => ['autofocus' => true, 'class' => 'form-control'],
                'label' => 'Title',
                'required' => true,
                ]
            )
            ->add(
                'summary',
                TextareaType::class,
                [
                'label' => 'Summary',
                'attr' => ['class' => 'form-control'],
                ]
            )
            ->add(
                'content',
                CKEditorType::class,
                [
                'config' => array(

                    'showWordCount' => true
                )
                ]
            )
            ->add(
                'tags',
                ChoiceType::class,
                [
                'choices'  => [
                    'Travel' => 'travel',
                    'Technology' => 'technology',
                    'News' => 'news',
                ],
                'attr' => ['class' => 'form-control'],
                ]
            )
            ->add(
                'thumbnail',
                FileType::class,
                [
                'label' => 'Thumbnail Image',
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
            )
            ->addEventListener(
                FormEvents::SUBMIT,
                function (FormEvent $event) {
                    $post = $event->getData();
                    if (null !== $postTitle = $post->getTitle()) {
                        $post->setSlug($this->slugger->slug($postTitle)->lower() . '-' . uniqid());
                    }
                }
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => Post::class,
            ]
        );
    }
}
