<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\Post1Type;
use App\Repository\PostRepository;
use App\Service\ParameterHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),

        ]);
    }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger,ParameterHelper $parameterHelper): Response
    {
        $posts = new Post();
        $form = $this->createForm(Post1Type::class, $posts);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $profile_picture_upload = $form->get('illustration')->getData();

            if($profile_picture_upload){
                $originalFilename = pathinfo($profile_picture_upload->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profile_picture_upload->guessExtension();

                try {
                    $post_directory = $parameterHelper->getRootDir() . '/public/images/user/' . $this->getUser()->getId();
                    
                    if(!file_exists($post_directory)){
                        $filesystem = new Filesystem();
                        $filesystem->mkdir($post_directory);
                        $filesystem->mkdir($post_directory . '/post');
                        $filesystem->mkdir($post_directory . '/account');
                    }

                    $profile_picture_upload->move($post_directory . '/post/', $newFilename);

                    $posts->setIllustration('/images/user/' . $this->getUser()->getId() . '/post/' . $newFilename);

                    $entityManager->persist($posts);
                    $entityManager->flush();

                    return $this->redirectToRoute('app_post_index');

                }catch(FileException $e){
                    throw new Exception("Error Processing Request", $e);
                    //TODO : Ajouter l'error handler
                }
            }



            $entityManager->persist($posts);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/new.html.twig', [
            'posts' => $posts,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $posts): Response
    {
        return $this->render('post/show.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $posts, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Post1Type::class, $posts);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/edit.html.twig', [
            'posts' => $posts,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $posts, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$posts->getId(), $request->request->get('_token'))) {
            $entityManager->remove($posts);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
