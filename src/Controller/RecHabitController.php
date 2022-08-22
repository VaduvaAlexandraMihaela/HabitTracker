<?php

namespace App\Controller;

use App\Entity\RecHabit;
use App\Form\RecHabitType;
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
        return $this->render('rec_habit/index.html.twig', [
            'rec_habits' => $recHabitRepository->findAll(),
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
}
