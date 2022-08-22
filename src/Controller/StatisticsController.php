<?php

namespace App\Controller;

use App\Repository\HabitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/statistics')]
class StatisticsController extends AbstractController{

    #[Route('/', name: 'statistics_index', methods: ['GET'])]
    public function index(HabitRepository $habitRepository): Response
    {
        $habits = $habitRepository->findAll();
        $names = array();
        $durations = array();

        foreach($habits as $habit){
            $duration = $habit->getTimeSpent();
            $name = $habit->getName();
            $percentageHour = floor((100 * $duration) / 24);
            $name = $name . " " . $percentageHour. "%";
            array_push($names,$name);
            array_push($durations,$duration);
        }
        return $this->render('statistics/index.html.twig', [
            'names' => $names,
            'durations' => $durations,
        ]);
    }
}