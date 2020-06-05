<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Form\ArticleSearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/search")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/", name="search")
     */
    public function index(ArticleRepository $articleRepository, Request $request)
    {
        $searchForm = $this->createForm(ArticleSearchType::class, null, [
            'action' => $this->generateUrl('search')
        ]);

        $searchForm->handleRequest($request);
        $results = [];
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            if ($data['s']) {
                if ($data['index'] == 'author') {
                    $results = $articleRepository->searchAuthor($data['s']);
                } else {
                    $results = $articleRepository->searchContent($data['s']);
                }
            }
        }
        return $this->render('search/index.html.twig', [
            'searchForm' => $searchForm->createView(),
            'results' => $results
        ]);
    }
}
