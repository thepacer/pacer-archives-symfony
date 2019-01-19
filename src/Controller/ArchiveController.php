<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Volume;
use App\Entity\Issue;
use App\Entity\Article;

/**
 * @Route("/archive")
 */
class ArchiveController extends AbstractController
{
    /**
     * @Route("/", name="archive")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $volumes = $entityManager->getRepository(Volume::class)->findAllCurrentVolumes();

        return $this->render('archive/index.html.twig', [
            'volumes' => $volumes
        ]);
    }

    /**
     * @Route("/volume-{volumeNumber}", name="volume")
     */
    public function volume(int $volumeNumber)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $volume = $entityManager->getRepository(Volume::class)->findOneBy(['volumeNumber' => $volumeNumber]);

        return $this->render('archive/volume.html.twig', [
            'volume' => $volume
        ]);
    }

    /**
     * @Route("/issue-{issueDate}", name="issue")
     */
    public function issue(string $issueDate)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $issue = $entityManager->getRepository(Issue::class)->findOneBy(['issueDate' => new \DateTime($issueDate)]);

        return $this->render('archive/issue.html.twig', [
            'issue' => $issue
        ]);
    }

    /**
     * @Route("/article/{slug}/{id}", name="article")
     */
    public function article(string $slug, int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->find($id);

        // Prevent slug manipulation
        if ($slug != $article->getSlug()) {
            return $this->redirectToRoute('article', [
                'id' => $article->getId(),
                'slug' => $article->getSlug()
            ], 301);
        }

        return $this->render('archive/article.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * Handle PacerCMS (Legacy) Article Links
     *
     * @Route("/legacy-article/{legacy_id}", name="legacy_article")
     */
    public function legacyArticle(int $legacy_id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->findOneBy(['legacyId' => $legacy_id]);

        // Handle not found
        if ($article === null) {
            return $this->createNotFoundException('Could not locate legacy article');
        }

        // Redirect to new route
        return $this->redirectToRoute('article', [
            'id' => $article->getId(),
            'slug' => $article->getSlug()
        ], 301);
    }
}
