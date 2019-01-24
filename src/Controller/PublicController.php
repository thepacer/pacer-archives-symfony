<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Simple\FilesystemCache;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\Issue;
use GuzzleHttp\Client as Client;

class PublicController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $cache = new FilesystemCache();
        $entityManager = $this->getDoctrine()->getManager();

        // Clear cache
        if ($request->get('clearCache')) {
            $cache->clear();
        }

        if (!$cache->has('public.pacer_site_feed')) {
            $client = new Client();
            $response = $client->get('http://www.thepacer.net/wp-json/wp/v2/posts?_embed&per_page=5');
            $cache->set('public.pacer_site_feed', json_decode($response->getBody()), 3600);
        }

        if (!$cache->has('public.issue_count')) {
            $cache->set('public.issue_count', $entityManager->getRepository(Issue::class)->countIssues(), 300);
        }

        $feed = $cache->get('public.pacer_site_feed');
        $issue_count = $cache->get('public.issue_count');

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
