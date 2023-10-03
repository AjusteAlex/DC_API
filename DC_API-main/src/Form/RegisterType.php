<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class RegisterType extends AbstractType
{
  
  public function buildForm(FormBuilderInterface $builder, array $options): void    
  {
    
    $builder
    ->add('email', EmailType::class, [
      'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
      'attr' => ['class' => 'mt-1 w-full rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm']
    ])
    ->add('firstname', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
        'label' =>'Veuillez saisir votre prénom',
      'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
      'attr' => ['class' => 'mt-1 w-full rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm']
    ])
    ->add('lastname', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
        'label' =>'Veuillez saisir votre nom',
      'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
      'attr' => ['class' => 'mt-1 w-full rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm']
    ])


   /* ->add('slug', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
        'label' =>'Votre slug',
        'mapped' => false,
        'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
        'attr' => ['class' => 'mt-1 w-full rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm']
    ])

        ->add('name', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
            'label' =>'Veuillez saisir votre nom complet',
            'mapped' => false,
            'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
            'attr' => ['class' => 'mt-1 w-full rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm']
        ])
   */

    ->add('password', RepeatedType::class, [
      'type' => PasswordType::class,
      'invalid_message' => 'Les mots de passe doivent correspondre.',
      'required' => true,
      'first_options' => [
        'label' => 'Mot de passe',
        'label_attr' => ['class' => 'mt-1 w-full rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm' ],
        'attr' => ['class' => 'mt-1 w-full rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm']
      ],
      
      'second_options' => [
        'label' => 'Confirmation du mot de passe',
        'label_attr' => ['class' => 'block text-sm font-medium text-gray-700' ],                                      
        'attr' => ['class' => 'mt-1 w-full rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm']
        ],       
      ])

    ->add("valider", SubmitType::class, [
      'attr' => ['class' => 'inline-block shrink-0 rounded-md border border-blue-600 bg-blue-600 px-12 py-3 text-sm font-medium text-white transition hover:bg-transparent hover:text-blue-600 focus:outline-none focus:ring active:text-blue-500']
    ]);
  }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
