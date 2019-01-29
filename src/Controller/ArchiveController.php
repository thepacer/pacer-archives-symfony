<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Aws\S3\S3Client;

use App\Entity\Volume;
use App\Entity\Image;
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
     * @Route("/volume-{volumeNumber}", name="volume", requirements={"volumeNumber"="\d+"})
     */
    public function volume(int $volumeNumber)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $volume = $entityManager->getRepository(Volume::class)->findOneBy(['volumeNumber' => $volumeNumber]);

        if (!$volume) {
            return $this->createNotFoundException('No matching volume found.');
        }

        $previousVolume = $entityManager->getRepository(Volume::class)->findPreviousVolume($volume);
        $nextVolume = $entityManager->getRepository(Volume::class)->findNextVolume($volume);

        return $this->render('archive/volume.html.twig', [
            'volume' => $volume,
            'previousVolume' => $previousVolume,
            'nextVolume' => $nextVolume
        ]);
    }

    /**
     * @Route("/issue-{issueDate}", name="issue", requirements={"issueDate"="([0-9]{2,4})-([0-1][0-9])-([0-3][0-9])"})
     */
    public function issue(string $issueDate)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $issue = $entityManager->getRepository(Issue::class)->findOneBy(['issueDate' => new \DateTime($issueDate)]);

        if (!$issue) {
            return $this->createNotFoundException('No matching issue found.');
        }

        $previousIssue = $entityManager->getRepository(Issue::class)->findPreviousIssue($issue);
        $nextIssue = $entityManager->getRepository(Issue::class)->findNextIssue($issue);

        return $this->render('archive/issue.html.twig', [
            'issue' => $issue,
            'previousIssue' => $previousIssue,
            'nextIssue' => $nextIssue
        ]);
    }

    /**
     * @Route("/article/{slug}/{id}", name="article", requirements={"id"="\d+"})
     */
    public function article(string $slug, int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            return $this->createNotFoundException('No matching article found.');
        }

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
     * S3 Proxy
     *
     * @Route("/image/{id}", name="s3_proxy", requirements={"id"="\d+"})
     */
    public function s3Proxy(S3Client $s3Client, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $image = $entityManager->getRepository(Image::class)->find($id);

        if (!$image) {
            return $this->createNotFoundException('No matching image found.');
        }

        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => 'pacer-archives',
            'Key'    => $image->getPath()
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');

        // Get the actual presigned-url
        $presignedUrl = (string)$request->getUri();

        return $this->redirect($presignedUrl);
    }

    /**
     * Handle PacerCMS (Legacy) Article Links
     *
     * @Route("/legacy-article/{id}", name="legacy_article", requirements={"id"="\d+"})
     */
    public function legacyArticle(int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->findOneBy(['legacyId' => $id]);

        if (!$article) {
            return $this->createNotFoundException('Could not locate legacy article');
        }

        // Redirect to new route
        return $this->redirectToRoute('article', [
            'id' => $article->getId(),
            'slug' => $article->getSlug()
        ], 301);
    }
}
