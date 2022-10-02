<?php

namespace App\Controller\Admin;

use App\Entity\Volume;
use App\Form\VolumeType;
use App\Repository\VolumeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/volume')]
#[IsGranted('ROLE_ADMIN')]
class VolumeController extends AbstractController
{
    #[Route(path: '', name: 'volume_index', methods: ['GET'])]
    public function index(VolumeRepository $volumeRepository): Response
    {
        return $this->render('admin/volume/index.html.twig', [
            'volumes' => $volumeRepository->findAll(),
        ]);
    }

    #[Route(path: '/new', name: 'volume_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $volume = new Volume();
        $form = $this->createForm(VolumeType::class, $volume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($volume);
            $entityManager->flush();

            return $this->redirectToRoute('volume_index');
        }

        return $this->render('admin/volume/new.html.twig', [
            'volume' => $volume,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'volume_show', methods: ['GET'])]
    public function show(Volume $volume): Response
    {
        return $this->render('admin/volume/show.html.twig', [
            'volume' => $volume,
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'volume_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Volume $volume, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VolumeType::class, $volume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            if ('public' == $request->query->get('return')) {
                return $this->redirectToRoute('volume', ['volumeNumber' => $volume->getVolumeNumber()]);
            }

            return $this->redirectToRoute('volume_index');
        }

        return $this->render('admin/volume/edit.html.twig', [
            'volume' => $volume,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'volume_delete', methods: ['DELETE'])]
    public function delete(Request $request, Volume $volume, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$volume->getId(), $request->request->get('_token'))) {
            $entityManager->remove($volume);
            $entityManager->flush();
        }

        return $this->redirectToRoute('volume_index');
    }
}
