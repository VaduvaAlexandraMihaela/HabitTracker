<?php

namespace App\Controller;

use App\Entity\Food;
use App\Form\FoodType;
use App\Repository\FoodRepository;
use App\Repository\WeightRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/food')]
class FoodController extends AbstractController
{
    #[Route('/', name: 'app_food_index', methods: ['GET'])]
    public function index(FoodRepository $foodRepository, WeightRepository $weightRepository): Response
    {
        $userId = $this->getUser();
        $weight =  $weightRepository->getWeightByUserId($userId);
        if(count($weight) > 0){
            return $this->render('food/index.html.twig', [
                'caloriesByDate' => $foodRepository->getTotalCaloriesByDate($userId),
                'foods' => $foodRepository->findAll(),
                'weight' => $weightRepository->getWeightByUserId($userId)
            ]);
        }else{
            return $this->redirectToRoute('app_weight_new', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/new', name: 'app_food_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FoodRepository $foodRepository): Response
    {
        $food = new Food();

        $user = $this->getUser();
        $food->setUserId($user);
        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $foodRepository->add($food, true);

            return $this->redirectToRoute('app_food_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('food/new.html.twig', [
            'food' => $food,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_food_show', methods: ['GET'])]
    public function show(Food $food): Response
    {
        return $this->render('food/show.html.twig', [
            'food' => $food,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_food_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Food $food, FoodRepository $foodRepository): Response
    {
        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $foodRepository->add($food, true);

            return $this->redirectToRoute('app_food_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('food/edit.html.twig', [
            'food' => $food,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_food_delete', methods: ['POST'])]
    public function delete(Request $request, Food $food, FoodRepository $foodRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$food->getId(), $request->request->get('_token'))) {
            $foodRepository->remove($food, true);
        }

        return $this->redirectToRoute('app_food_index', [], Response::HTTP_SEE_OTHER);
    }
}
