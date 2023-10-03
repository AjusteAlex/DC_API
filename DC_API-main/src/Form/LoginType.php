<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

    $builder    
    ->add('email', EmailType::class, [
      'label_attr' => ['class' => 'label'],
      'attr' => [
        'class' => 'mt-1 w-full rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm',
        'name' => 'email',
        'id' => 'inputEmail',
        'required' => true
      ]
    ])
    ->add('password', PasswordType::class, [
      'label' => 'Mot de passe',
      'label_attr' => [
        'class' => 'label'
      ],
      'attr' => [
        'class' => 'input w-full input-bordered',
        'name' => 'password',
        'id' => 'inputPassword',
        'required' => true
      ]
    ])
    ->add('Valider', SubmitType::class, [
      'attr' => [
        'class' => 'btn btn-primary'
      ]
    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
