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
        return $this->render('public/index.html.twig');
    }

    /**
     * @Route("/missing-issues", name="missing-issues")
     */
    public function missingIssues()
    {
        return $this->render('public/missing-issues.html.twig');
    }
}
