<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/movie', name: 'app_movie', methods: ['GET','HEAD'])]
    public function index(): Response
    {
        $cars = array("Volvo", "BMW", "Toyota");
        return  $this->render('index.html.twig',array(
            'cars'=>$cars
        ));
    }
}
