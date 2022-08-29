<?php

namespace App\Controller;

use App\Entity\Weight;
use App\Form\WeightType;
use App\Repository\WeightRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/weight')]
class WeightController extends AbstractController
{
    #[Route('/', name: 'app_weight_index', methods: ['GET'])]
    public function index(WeightRepository $weightRepository): Response
    {
        return $this->render('weight/index.html.twig', [
            'weights' => $weightRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_weight_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WeightRepository $weightRepository): Response
    {
        $weight = new Weight();
        $form = $this->createForm(WeightType::class, $weight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $weightRepository->add($weight, true);

            return $this->redirectToRoute('app_weight_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('weight/new.html.twig', [
            'weight' => $weight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weight_show', methods: ['GET'])]
    public function show(Weight $weight): Response
    {
        return $this->render('weight/show.html.twig', [
            'weight' => $weight,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_weight_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Weight $weight, WeightRepository $weightRepository): Response
    {
        $form = $this->createForm(WeightType::class, $weight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $weightRepository->add($weight, true);

            return $this->redirectToRoute('app_weight_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('weight/edit.html.twig', [
            'weight' => $weight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weight_delete', methods: ['POST'])]
    public function delete(Request $request, Weight $weight, WeightRepository $weightRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weight->getId(), $request->request->get('_token'))) {
            $weightRepository->remove($weight, true);
        }

        return $this->redirectToRoute('app_weight_index', [], Response::HTTP_SEE_OTHER);
    }
}
