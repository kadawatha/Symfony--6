<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository)
    {
        $this->em = $em;
        $this->movieRepository = $movieRepository;
    }


    #[Route('/movies/create', name: 'movie.create')]
    public function create(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class,$movie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
           $newMovie = $form->getData();

           $imagePath = $form->get('imagePath')->getData();

           if ($imagePath)
           {
               $newFileName = uniqid().'.'. $imagePath->guessExtension();

               try {
                   $imagePath->move(
                     $this->getParameter('kernel.project_dir').'/public/uploads',
                     $newFileName
                   );
               } catch (FileException $e) {
                    return new Response($e->getMessage());
               }

               $newMovie->setImagePath('/uploads/'.$newFileName);

           }

           $this->em->persist($newMovie);
           $this->em->flush();

           return $this->redirectToRoute('movie.index');

        }

        return $this->render('movie/create.html.twig',['form'=>$form->createView()]);
    }


    #[Route('/movies', name: 'movie.index', methods: ['GET','HEAD'])]
    public function index(): Response
    {
        $repository = $this->em->getRepository(Movie::class);
        $movies = $repository->findAll();

        return $this->render('movie/index.html.twig',['movies'=>$movies]);

    }

    #[Route('/movies/{id}', name: 'movie.show', methods: ['GET','HEAD'])]
    public function show($id): Response
    {
        $repository = $this->em->getRepository(Movie::class);
        $movie = $repository->find($id);

        //dd($movie);

        return $this->render('movie/show.html.twig',['movie'=>$movie]);

    }

    #[Route('/movies/edit/{id}', name: 'edit.movie')]
    public function edit($id, Request $request): Response
    {
        $movie = $this->movieRepository->find($id);
        $form = $this->createForm(MovieFormType::class,$movie);

        $form->handleRequest($request);
        $imagePath = $form->get('imagePath')->getData();

        if ($form->isSubmitted() && $form->isValid())
        {
            if($imagePath)
            {

                // handle image upload

            } else
            {
              $movie->setTitle($form->get('title')->getData());
              $movie->setReleaseYear($form->get('releaseYear')->getData());
              $movie->setDescription($form->get('description')->getData());

              $this->em->flush();
              return $this->redirectToRoute('movie.index');

            }
        }

        return $this->render('movie/edit.html.twig',['movie'=>$movie,'form'=>$form->createView()]);

    }


}
