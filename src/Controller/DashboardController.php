<?php

namespace App\Controller;

//use App\Repository\HabitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController{

//    /**
//     * @Route ("dashboard/",name="dashboard")
//     * @param HabitRepository $habitRepository
//     * @return Response
//     */
//
//    #[Route('/dashboard', name: 'dashboard')]
//    public function index(HabitRepository $habitRepository): Response
//    {
//        $habits = $habitRepository->getAll();
//          return $this->render('Habits/dashboard.html.twig',[
//              'habits' => $habits
//          ]);
//    }
}