<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Volume;

class PublicController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $volumes = $entityManager->getRepository(Volume::class)->findAllCurrentVolumes();

        return $this->render('public/index.html.twig', [
            'volumes' => $volumes
        ]);
    }

    /**
     * @Route("/missing-issues", name="missing-issues")
     */
    public function missingIssues()
    {
        return $this->render('public/missing-issues.html.twig');
    }
}
