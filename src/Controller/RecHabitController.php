<?php

namespace App\Controller;

use App\Entity\Habit;
use App\Entity\RecHabit;
use App\Form\RecHabitType;
use App\Repository\HabitRepository;
use App\Repository\RecHabitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rec/habit')]
class RecHabitController extends AbstractController
{
    #[Route('/', name: 'app_rec_habit_index', methods: ['GET'])]
    public function index(RecHabitRepository $recHabitRepository): Response
    {
        $recHabits = $recHabitRepository->findAll();
        $percentages = array();

        foreach($recHabits as $recHabit){
            foreach($recHabits as $recHabitCompare){
                similar_text($recHabit->getName(),$recHabitCompare->getName(), $similarity);
                $similarity = array($recHabit->getName() => $similarity);
                array_push($percentages,$similarity);
            }
        }

        $percentages = array_unique($percentages,SORT_REGULAR);

        foreach($percentages as $key => $percentage) {
            if (count(array_keys($percentage, 0)) != 0) {
                unset($percentages[$key]);
            }
            $values = array_values($percentage);
            foreach ($values as $value) {
                if($value < 60){
                    unset($percentages[$key]);
                }
                if($value == 100){
                    unset($percentages[$key]);
                }
            }
        }


        $percentages = array_map("unserialize", array_unique(array_map("serialize", $percentages)));
        $newPercentage = array();
        foreach($percentages as $key => $percentage){
            $values = array_keys($percentage);
            foreach($values as $value){
                foreach($values as $comparedValue){
                    similar_text($value,$comparedValue,$similarity);

                    if($similarity > 50){
                        $similarity = array($similarity => $value);
                        array_push($newPercentage, $similarity);
                    }
                }
            }
        }
        $newPercentage = array_map("unserialize", array_unique(array_map("serialize", $newPercentage)));

        $finalRecs = array();
        foreach($recHabits as $recHabit){
            foreach($newPercentage as $newPerc) {
                foreach($newPerc as $percentageValue){
                    if ($recHabit->getName() == $percentageValue) {
                        array_push($finalRecs, $recHabit);
                    }
                }
            }
        }

        $finalRecs = array_map("unserialize", array_unique(array_map("serialize", $finalRecs)));

        return $this->render('rec_habit/index.html.twig', [
            'rec_habits' => $finalRecs,
        ]);
    }

    #[Route('/new', name: 'app_rec_habit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecHabitRepository $recHabitRepository): Response
    {
        $recHabit = new RecHabit();
        $form = $this->createForm(RecHabitType::class, $recHabit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recHabitRepository->add($recHabit, true);
            return $this->redirectToRoute('app_rec_habit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rec_habit/new.html.twig', [
            'rec_habit' => $recHabit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rec_habit_show', methods: ['GET'])]
    public function show(RecHabit $recHabit): Response
    {
        return $this->render('rec_habit/show.html.twig', [
            'rec_habit' => $recHabit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rec_habit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RecHabit $recHabit, RecHabitRepository $recHabitRepository): Response
    {
        $form = $this->createForm(RecHabitType::class, $recHabit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recHabitRepository->add($recHabit, true);

            return $this->redirectToRoute('app_rec_habit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rec_habit/edit.html.twig', [
            'rec_habit' => $recHabit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rec_habit_delete', methods: ['POST'])]
    public function delete(Request $request, RecHabit $recHabit, RecHabitRepository $recHabitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recHabit->getId(), $request->request->get('_token'))) {
            $recHabitRepository->remove($recHabit, true);
        }

        return $this->redirectToRoute('app_rec_habit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/', name: 'app_rec_habit_index_top', methods: ['GET'])]
    public function getUserRecommendations(RecHabitRepository $recHabitRepository): Response
    {
        $recHabits = $recHabitRepository->findAll();
        $percentages = array();

        foreach($recHabits as $recHabit){
            foreach($recHabits as $recHabitCompare){
                similar_text($recHabit->getName(),$recHabitCompare->getName(), $similarity);
                array_push($percentages,$similarity);
            }
        }

        $topHabits = array();
        rsort($percentages);
        $countTopPercentages = (30*count($percentages))/100;
        $topPercentages = array_slice($percentages,0,$countTopPercentages);

        foreach($recHabits as $recHabit){
            foreach($recHabits as $recHabitCompare){
                similar_text($recHabit->getName(),$recHabitCompare->getName(), $similarity);

                foreach($topPercentages as $percentage){
                    if($similarity == $percentage){
                        array_push($topHabits,$recHabit);
                    }
                }
            }
        }

        return $this->render('rec_habit/top_index.html.twig', [
            'rec_habits' => $topHabits,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_rec_habit_addHabit', methods: ['GET', 'POST'])]
    public function AddRecHabit(Request $request, RecHabit $recHabit, RecHabitRepository $recHabitRepository, HabitRepository $habitRepository): Response
    {
        $form = $this->createForm(RecHabitType::class, $recHabit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recHabitRepository->add($recHabit, true);

            return $this->redirectToRoute('app_rec_habit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rec_habit/edit.html.twig', [
            'rec_habit' => $recHabit,
            'form' => $form,
        ]);
    }
}
