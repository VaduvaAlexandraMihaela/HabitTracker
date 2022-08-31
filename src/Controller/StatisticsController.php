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
        $userId = $this->getUser();
        $habitsToday = $habitRepository->getHabitsToday($userId);
        $habitsYesterday = $habitRepository->getHabitsYesterday($userId);
        $habitsLastWeek = $habitRepository->getHabitsLastWeek($userId);
        $namesToday = array();
        $durationsToday = array();
        $namesYesterday = array();
        $durationsYesterday = array();
        $namesLastWeek = array();
        $durationsLastWeek = array();

        foreach($habitsToday as $habit){
            $duration = $habit->getTimeSpent();
            $name = $habit->getName();
            $percentageHour = floor((100 * $duration/60) / 24);
            $name = $name . " " . $percentageHour. "%";
            array_push($namesToday,$name);
            array_push($durationsToday,$duration);
        }
        foreach($habitsYesterday as $habit){
            $duration = $habit->getTimeSpent();
            $name = $habit->getName();
            $percentageHour = floor((100 * $duration/60) / 24);
            $name = $name . " " . $percentageHour. "%";
            array_push($namesYesterday,$name);
            array_push($durationsYesterday,$duration);
        }
        foreach($habitsLastWeek as $habit){
            $duration = $habit->getTimeSpent();
            $name = $habit->getName();
            $percentageHour = floor((100 * $duration/60) / 24);
            $name = $name . " " . $percentageHour. "%";
            array_push($namesLastWeek,$name);
            array_push($durationsLastWeek,$duration);
        }
        return $this->render('statistics/index.html.twig', [
            'namesToday' => $namesToday,
            'durationsToday' => $durationsToday,
            'namesYesterday' => $namesYesterday,
            'durationsYesterday' => $durationsYesterday,
            'namesLastWeek' => $namesLastWeek,
            'durationsLastWeek' => $durationsLastWeek
        ]);
    }
}