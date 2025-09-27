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
    public const PACER_SITE_FEED = 'https://www.thepacer.net/wp-json/wp/v2/posts?_embed&per_page=5';
    public const PACER_RSS_FEED = 'https://www.thepacer.net/feed/';
    public const FEED_REQUEST_DELAY = 2; // seconds

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

            // Create a temporary cookie jar for this request
            $tmpCookieFile = tempnam(sys_get_temp_dir(), 'pacer_cookies');

            $client = HttpClient::create([
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'application/json,text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.5',
                    'Accept-Encoding' => 'gzip, deflate',
                    'DNT' => '1',
                    'Connection' => 'keep-alive',
                    'Upgrade-Insecure-Requests' => '1',
                ],
                'timeout' => 15,
                'cafile' => false, // For development, consider removing in production
            ]);

            // First, try to get the main page to establish session/cookies
            try {
                sleep(1); // Brief delay
                $response = $client->request('GET', 'https://www.thepacer.net/', [
                    'headers' => [
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    ],
                ]);

                // Extract cookies from response if any
                $setCookieHeaders = $response->getHeaders()['set-cookie'] ?? [];
                $cookieString = '';
                foreach ($setCookieHeaders as $cookie) {
                    $cookiePart = explode(';', $cookie)[0];
                    $cookieString .= $cookiePart . '; ';
                }
            } catch (\Exception $e) {
                $cookieString = '';
            }

            // Try WP-JSON API first
            try {
                sleep(self::FEED_REQUEST_DELAY);
                $headers = [
                    'Referer' => 'https://www.thepacer.net/',
                ];
                if ($cookieString) {
                    $headers['Cookie'] = rtrim($cookieString, '; ');
                }

                $response = $client->request('GET', self::PACER_SITE_FEED, [
                    'headers' => $headers,
                ]);

                if ($response->getStatusCode() === 200) {
                    $content = $response->getContent();
                    $decoded = json_decode($content, true);

                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        @unlink($tmpCookieFile);
                        return $decoded;
                    }
                }
            } catch (\Exception $e) {
                // WP-JSON failed, try RSS feed
                try {
                    sleep(self::FEED_REQUEST_DELAY);
                    $headers = [
                        'Accept' => 'application/rss+xml,application/xml,text/xml,*/*',
                        'Referer' => 'https://www.thepacer.net/',
                    ];
                    if ($cookieString) {
                        $headers['Cookie'] = rtrim($cookieString, '; ');
                    }

                    $response = $client->request('GET', self::PACER_RSS_FEED, [
                        'headers' => $headers,
                    ]);

                    if ($response->getStatusCode() === 200) {
                        $content = $response->getContent();
                        // Parse RSS and convert to similar format as WP-JSON
                        $rssData = $this->parseRssFeed($content);
                        if ($rssData !== false) {
                            @unlink($tmpCookieFile);
                            return $rssData;
                        }
                    }
                } catch (\Exception $rssException) {
                    // Both feeds failed
                }
            }

            @unlink($tmpCookieFile);
            $cache->delete('public.pacer_site_feed');
            return false;
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

    /**
     * Parse RSS feed content and convert to format similar to WP-JSON API
     */
    private function parseRssFeed(string $rssContent): array|false
    {
        try {
            $xml = new \SimpleXMLElement($rssContent);
            $items = [];

            foreach ($xml->channel->item as $item) {
                $items[] = [
                    'title' => ['rendered' => (string) $item->title],
                    'link' => (string) $item->link,
                    'date' => date('Y-m-d\TH:i:s', strtotime((string) $item->pubDate)),
                    '_embedded' => [
                        'author' => [
                            ['name' => 'The Pacer Staff'] // Default author for RSS
                        ]
                    ]
                ];
            }

            return array_slice($items, 0, 5); // Limit to 5 items like WP-JSON
        } catch (\Exception $e) {
            return false;
        }
    }
}
