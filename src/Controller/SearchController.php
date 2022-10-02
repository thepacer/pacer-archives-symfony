<?php

namespace App\Controller;

use App\Form\ArticleSearchType;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/search')]
class SearchController extends AbstractController
{
    #[Route(path: '/', name: 'search')]
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request)
    {
        $searchForm = $this->createForm(ArticleSearchType::class, null, [
            'method' => 'GET',
            'action' => $this->generateUrl('search'),
            's' => $request->query->get('s'),
            'index' => $request->query->get('index', 'content'),
        ]);

        $pagination = false;

        if ($request->query->get('s')) {
            if ('author' == $request->query->get('index')) {
                $query = $articleRepository->searchAuthor($request->query->get('s'));
            } else {
                $query = $articleRepository->searchContent($request->query->get('s'));
            }
            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                10,
                ['wrap-queries' => true]
            );
        }

        return $this->render('search/index.html.twig', [
            'searchForm' => $searchForm->createView(),
            'pagination' => $pagination,
        ]);
    }
}
