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

        if (!$cache->has('public.pacer_feed')) {
            $client = new Client();
            $response = $client->get('http://www.thepacer.net/wp-json/wp/v2/posts?_embed&per_page=5');
            $feed = json_decode($response->getBody());
            $cache->set('public.pacer_feed', $feed);
        }

        $feed = $cache->get('public.pacer_feed');

        return $this->render('public/index.html.twig', [
            'feed' => $feed
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
