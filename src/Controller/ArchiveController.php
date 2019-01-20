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
     * S3 Proxy
     *
     * @Route("/image/{image_id}", name="s3_proxy")
     */
    public function s3Proxy($image_id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $image = $entityManager->getRepository(Image::class)->find($image_id);

        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => 'us-east-1'
        ]);

        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => 'pacer-archives',
            'Key'    => $image->getPath(),
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');

        // Get the actual presigned-url
        $presignedUrl = (string)$request->getUri();

        return $this->redirect($presignedUrl);
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
