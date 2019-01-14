<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Simple\FilesystemCache;

use GuzzleHttp\Client as Client;

class PublicController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $cache = new FilesystemCache();
        $entityManager = $this->getDoctrine()->getManager();

        if (!$cache->has('public.pacer_site_feed')) {
            $client = new Client();
            $response = $client->get('http://www.thepacer.net/wp-json/wp/v2/posts?_embed&per_page=5');
            $feed = json_decode($response->getBody());
            $cache->set('public.pacer_site_feed', $feed);
        }

        if (!$cache->has('public.archived_issue_count')) {
            $qb = $entityManager->createQueryBuilder();
            $qb->select('count(issue.id)');
            $qb->from('App\Entity\Issue', 'issue');
            $cache->set('public.archived_issue_count', $qb->getQuery()->getSingleScalarResult());
        }

        $feed = $cache->get('public.pacer_site_feed');
        $issue_count = $cache->get('public.archived_issue_count');

        return $this->render('public/index.html.twig', [
            'feed' => $feed,
            'issue_count' => $issue_count
        ]);
    }

    /**
     * @Route("/missing-issues", name="missing_issues")
     */
    public function missingIssues()
    {
        return $this->render('public/missing-issues.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('public/about.html.twig');
    }

    /**
     * @Route("/donate", name="donate")
     */
    public function donate()
    {
        return $this->render('public/donate.html.twig');
    }
}
