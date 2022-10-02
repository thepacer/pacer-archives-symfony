<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
#[Route(path: '/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route(path: '', name: 'home')]
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }
}
