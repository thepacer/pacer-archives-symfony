<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $volumes = [];
        foreach (range(1928, 2019) as $value) {
            $volumes[] = [
                'number' => $value - 1927,
                'yearStart' => $value,
                'yearEnd' => $value + 1,
                'issueCount' => 0
            ];
        }

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
