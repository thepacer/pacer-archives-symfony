<?php

namespace App\Controller\Admin;

use App\Entity\Volume;
use App\Entity\Issue;
use App\Form\VolumeType;
use App\Repository\VolumeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/volume")
 */
class VolumeController extends AbstractController
{
    /**
     * @Route("/", name="admin_volume_index", methods={"GET"})
     */
    public function index(VolumeRepository $volumeRepository): Response
    {
        return $this->render('admin/volume/index.html.twig', [
            'volumes' => $volumeRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="admin_volume_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $volume = new Volume();
        $form = $this->createForm(VolumeType::class, $volume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($volume);
            $entityManager->flush();

            return $this->redirectToRoute('admin_volume_index');
        }

        return $this->render('admin/volume/new.html.twig', [
            'volume' => $volume,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_volume_show", methods={"GET"})
     */
    public function show(Volume $volume): Response
    {
        return $this->render('admin/volume/show.html.twig', ['volume' => $volume]);
    }

    /**
     * @Route("/{id}/edit", name="admin_volume_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Volume $volume): Response
    {
        $form = $this->createForm(VolumeType::class, $volume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_volume_index', ['id' => $volume->getId()]);
        }

        return $this->render('admin/volume/edit.html.twig', [
            'volume' => $volume,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_volume_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Volume $volume): Response
    {
        if ($this->isCsrfTokenValid('delete'.$volume->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($volume);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_volume_index');
    }
}
