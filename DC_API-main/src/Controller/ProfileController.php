<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $profiles = $this->entityManager->getRepository(User::class)->findAll();
        return $this->render('profile/index.html.twig',[
            'profiles' => $profiles
     ]);
    }
}
