<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Volume;
use App\Entity\Issue;

class ArchiveController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $volumes = $entityManager->getRepository(Volume::class)->findAllCurrentVolumes();

        return $this->render('archive/index.html.twig', [
            'volumes' => $volumes
        ]);
    }

    /**
     * @Route("/volume-{volumeNumber}", name="volume")
     */
    public function volume(int $volumeNumber)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $volume = $entityManager->getRepository(Volume::class)->findOneBy(['volumeNumber' => $volumeNumber]);

        return $this->render('archive/volume.html.twig', [
            'volume' => $volume
        ]);
    }

    /**
     * @Route("/issue-{archiveKey}", name="issue")
     */
    public function issue(string $archiveKey)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $issue = $entityManager->getRepository(Issue::class)->findOneBy(['archiveKey' => $archiveKey]);

        return $this->render('archive/issue.html.twig', [
            'issue' => $issue
        ]);
    }
}
