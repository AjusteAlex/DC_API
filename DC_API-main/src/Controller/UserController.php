<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegisterType;
use App\Form\UserSetupAccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Authentication\AuthenticationService;
use App\Service\ParameterHelper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
  public function __construct(SluggerInterface $slugger){
      $this->slugger = $slugger;
  }

  #[Route('/user', name: 'app_user')]
  public function index(): JsonResponse
  {
     return $this->json([
        'message' => 'Welcome to your new controller!',
        'path' => 'src/Controller/UserController.php',
     ]);
  }

  #[Route('/register', name: 'app_user_register')]
  public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
  {
    $form = $this->createForm(RegisterType::class);
    
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
      $form_data = $form->getData();
      
      $userRepository = $entityManager->getRepository(User::class);
      
      $userExists = $userRepository->findOneBy(['email' => $form_data->getEmail()]);                                                                 
      
      if($userExists){
        return $this->json([
          'status' => 'error',
          'message' => "Un compte existe déjà avec cette adresse email"
        ]);
      }

      $user = new User();
      $user->setEmail($form_data->getEmail());
      $user->setPassword($form_data->getPassword());
      $user->setFirstname($form_data->getFirstname());
      $user->setLastname($form_data->getLastname());
      $user->setPassword($passwordHasher->hashPassword($user, $form_data->getPassword()));
      $user->setRoles(['ROLE_USER']);
      $user->setSlug($this->slugger->slug($form_data->getFirstname() . ' ' . $form_data->getLastname()));
      $entityManager->persist($user);
      $entityManager->flush();

      return $this->redirectToRoute('app_login');
    }

    return $this->render('authentication/register.html.twig', ['form' => $form]);
  }

  #[Route('/setup-account', name: 'app_setup_account')]
  public function setupAccount(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager, ParameterHelper $parameterHelper): Response 
  {
    $user = $this->getUser();

    $form = $this->createForm(UserSetupAccountType::class);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
      $profile_picture_upload = $form->get('profile_picture')->getData();

      if($profile_picture_upload){
        $originalFilename = pathinfo($profile_picture_upload->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$profile_picture_upload->guessExtension();

        try {
          $user_directory = $parameterHelper->getRootDir() . '/public/images/user/' . $user->getId();
          //$profile_picture_upload->
          if(!file_exists($user_directory)){  
            $filesystem = new Filesystem();
            $filesystem->mkdir($user_directory); 
            $filesystem->mkdir($user_directory . '/post');
            $filesystem->mkdir($user_directory . '/account');
          }

          $profile_picture_upload->move($user_directory . '/account', $newFilename);

          $form_data = $form->getData();

          $user->setIllustration('images/user/' . $user->getId() . '/account/' . $newFilename);
          
          $user->setSchool($form_data['school']);

          $entityManager->persist($user);
          $entityManager->flush();

          return $this->redirectToRoute('app_post_index');

        }catch(FileException $e){
          throw new Exception("Error Processing Request", $e);
          //TODO : Ajouter l'error handler
        }
      }
    }


    return $this->render('user/account-setup/index.html.twig', [
      'form' => $form
    ]);
  }

}
