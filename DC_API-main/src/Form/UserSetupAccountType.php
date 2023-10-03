<?php

namespace App\Form;

use App\Entity\School;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserSetupAccountType extends AbstractType
{
 
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    
    $builder
    ->add('profile_picture', FileType::class, [
      'label' => 'Votre photo de profil',
      'attr' => ['class' => 'file-input file-input-bordered w-full'],
      'mapped' => false,
      'constraints' => [
        new File([
          'mimeTypes' => [
            'image/jpeg',
            'image/jpg',
            'image/png'
          ],
          'mimeTypesMessage' => 'Veuillez utiliser un format d\'image correct.'
        ])
      ]
    ])
    ->add('school', EntityType::class, [
      'class' => School::class,
      'choice_label' => 'name',
      'attr' => [
        'class' => "input input-bordered w-full"
      ]
    ])
    ->add('valider', SubmitType::class, [
      'attr' => ['class' => 'btn btn-primary w-full']
    ]) 
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
     $resolver->setDefaults([
           // Configure your form options her
    ]);
  }
}
