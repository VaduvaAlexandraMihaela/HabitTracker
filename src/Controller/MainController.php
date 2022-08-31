<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController{

    #[Route('/', name: 'home_page', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }
}