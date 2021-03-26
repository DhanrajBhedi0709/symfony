<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostType extends AbstractType
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['autofocus' => true, 'class' => 'form-control'],
                'label' => 'Title',
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'Summary',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('content', TextareaType::class, [
                'attr' => ['rows' => 10, 'class' => 'form-control'],
                'label' => 'Content',
            ])
            ->add('tags',  ChoiceType::class, [
                'choices'  => [
                    'Movie' => 'movie',
                    'Technology' => 'technology',
                    'News' => 'news',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /** @var Post */
                $post = $event->getData();
                if (null !== $postTitle = $post->getTitle()) {
                    $post->setSlug($this->slugger->slug($postTitle)->lower());
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
