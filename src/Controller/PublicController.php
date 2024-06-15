<?php

namespace App\Controller;

use App\Repository\IssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;

class PublicController extends AbstractController
{
    public const CACHE_TTL = 3600;
    public const DONATION_URL = 'https://give.utm.edu/campaigns/42936/donations/new?designation=PACER_03';
    public const PACER_SITE_FEED = 'http://www.thepacer.net/wp-json/wp/v2/posts?_embed&per_page=5';

    #[Route(path: '/', name: 'home')]
    public function index(IssueRepository $issueRepository, Request $request)
    {
        $cache = new FilesystemAdapter();

        // Clear cache
        if ($request->get('clearCache')) {
            $cache->delete('public.pacer_site_feed');
            $cache->delete('public.issue_count');
        }

        $feed = $cache->get('public.pacer_site_feed', function (ItemInterface $item) use ($cache) {
            $item->expiresAfter(self::CACHE_TTL);
            $client = HttpClient::create();
            try {
                $response = $client->request('GET', self::PACER_SITE_FEED);

                return json_decode($response->getContent());
            } catch (ServerException $e) {
                $cache->delete('public.pacer_site_feed');

                return false;
            }
        });
        $issue_count = $cache->get('public.issue_count', function (ItemInterface $item) use ($issueRepository) {
            $item->expiresAfter(self::CACHE_TTL);

            return $issueRepository->getTotalIssueCount();
        });

        return $this->render('public/index.html.twig', [
            'feed' => $feed,
            'issue_count' => $issue_count,
            'opengraph' => [
                'title' => 'The Pacer',
                'description' => 'The student newspaper of the University of Tennessee at Martin since 1928.',
            ],
        ]);
    }

    #[Route(path: '/missing-issues', name: 'missing_issues')]
    public function missingIssues()
    {
        return $this->render('public/missing-issues.html.twig', [
            'opengraph' => [
                'title' => 'Missing Issues',
                'description' => 'We have collected a list of issues that are not available in our collection.',
            ],
        ]);
    }

    #[Route(path: '/about', name: 'about')]
    public function about()
    {
        return $this->render('public/about.html.twig', [
            'opengraph' => [
                'title' => 'About The Pacer',
                'description' => 'The newspaper archives are a collective effort of several people and organizations.',
            ],
        ]);
    }

    #[Route(path: '/donate', name: 'donate')]
    public function donate()
    {
        return $this->redirect(self::DONATION_URL);
    }
}
