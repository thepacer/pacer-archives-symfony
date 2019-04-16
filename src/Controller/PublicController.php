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
            $cache->set('public.issue_count', $entityManager->getRepository(Issue::class)->getTotalIssueCount(), 300);
        }

        $feed = $cache->get('public.pacer_site_feed');
        $issue_count = $cache->get('public.issue_count');

        return $this->render('public/index.html.twig', [
            'feed' => $feed,
            'issue_count' => $issue_count,
            'opengraph' => [
                'title' => 'The Pacer',
                'description' => 'The student newspaper of the University of Tennessee at Martin since 1928.'
            ]
        ]);
    }

    /**
     * @Route("/missing-issues", name="missing_issues")
     */
    public function missingIssues()
    {
        return $this->render('public/missing-issues.html.twig', [
            'opengraph' => [
                'title' => 'Missing Issues',
                'description' => 'We have collected a list of issues that are not available in our collection.'
            ]
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('public/about.html.twig', [
            'opengraph' => [
                'title' => 'About The Pacer',
                'description' => 'The newspaper archives are a collective effort of several people and organizations.'
            ]
        ]);
    }

    /**
     * @Route("/donate", name="donate")
     */
    public function donate()
    {
        return $this->render('public/donate.html.twig', [
            'opengraph' => [
                'title' => 'Support The Pacer',
                'description' => 'Your financial contributions helps advance journalism education at UT Martin.'
            ]
        ]);
    }
}
