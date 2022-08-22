<?php

namespace App\Controller;

use App\Entity\Habit;
use App\Entity\RecHabit;
use App\Entity\User;
use App\Form\HabitType;
use App\Repository\HabitRepository;
use App\Repository\RecHabitRepository;
use App\Repository\UserRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/habit')]
class HabitController extends AbstractController
{
    #[Route('/', name: 'app_habit_index', methods: ['GET'])]
    public function index(HabitRepository $habitRepository): Response
    {

        return $this->render('habit/index.html.twig', [
            'habitsYesterday' => $habitRepository->getHabitsYesterday(),
            'habitsToday' => $habitRepository->getHabitsToday()
        ]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/new', name: 'app_habit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HabitRepository $habitRepository, RecHabitRepository $recHabitRepository): Response
    {
        $habit = new Habit();

        $form = $this->createForm(HabitType::class, $habit);
        $form->handleRequest($request);

        $user = $this->getUser();
        $habit->setUserId($user);
        if ($form->isSubmitted() && $form->isValid()) {
            $habitRepository->add($habit, $recHabitRepository,true);

            return $this->redirectToRoute('app_habit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('habit/new.html.twig', [
            'habit' => $habit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_habit_show', methods: ['GET'])]
    public function show(Habit $habit): Response
    {
        return $this->render('habit/show.html.twig', [
            'habit' => $habit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_habit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Habit $habit, HabitRepository $habitRepository): Response
    {
        $form = $this->createForm(HabitType::class, $habit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $habitRepository->add($habit, true);

            return $this->redirectToRoute('app_habit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('habit/edit.html.twig', [
            'habit' => $habit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_habit_delete', methods: ['POST'])]
    public function delete(Request $request, Habit $habit, HabitRepository $habitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$habit->getId(), $request->request->get('_token'))) {
            $habitRepository->remove($habit, true);
        }

        return $this->redirectToRoute('app_habit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('{/LastWeek}', name: 'app_habit_index_week_last', methods: ['GET'])]
    public function getHabitsLastWeek(HabitRepository $habitRepository): Response
    {
        return $this->render('habit/habit_index_week_last.html.twig', [
            'habits' => $habitRepository->getHabitsLastWeek(),
        ]);
    }
}
