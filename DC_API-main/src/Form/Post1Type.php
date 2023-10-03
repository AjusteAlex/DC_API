<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class Post1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('title', TextType::class, [
      'attr' => [
        'class' => 'block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring'
      ],
      'label' => 'Titre',
      'label_attr' => ['class' => 'text-white']
    ])
    ->add('description', TextareaType::class, [
      'attr' => [
        'class' => 'block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring'
      ],
      'label' => 'Description',
      'label_attr' => ['class' => 'text-white']
    ])
            ->add('illustration', FileType::class,[
                'label' => 'Veuillez téléverser une photo de couverture pour votre offre',
                'required' => true,
              
                'attr' => [
        'accept' => 'image/*', // Restrict file types to images
        'placeholder' => "Téléverser une photo de couverture pour votre offre"
            ]
            ])
    ->add('category', EntityType::class, [
      'class' => Category::class,
      'choice_label' => 'name',
      'multiple' => true,
      'label' => 'Catégorie',
      'label_attr' => ['class' => 'text-white'],
      'attr' => [
        'class' => 'block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring' 
      ] 
    ])
    ->add('publier', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
