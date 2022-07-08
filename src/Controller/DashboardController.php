<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController{

    /**
     * @Route ("dashboard/",name="dashboard")
     * @return Response
     */

    #[Route('/dashboard', name: 'dashboard')]
    public function index(): Response
    {
          return $this->render('Habits/dashboard.html.twig');
    }
}