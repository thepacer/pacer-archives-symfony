<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
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

    const START_YEAR = 1928;

    /**
     * @Route("/", name="archive")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $volumes = $entityManager->getRepository(Volume::class)->findAllCurrentVolumes();
        $issues = $entityManager->getRepository(Issue::class)->findAll();

        $years = range(self::START_YEAR, date('Y'));
        $issue_counts_by_year = [];
        foreach ($years as $year) {
            $issue_counts_by_year[$year] = 0;
        }
        foreach ($issues as $issue) {
            $issue_counts_by_year[$issue->getIssueDate()->format('Y')]++;
        }

        return $this->render('archive/index.html.twig', [
            'issue_counts_by_year' => $issue_counts_by_year,
            'volumes' => $volumes,
            'years' => $years
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
            throw $this->createNotFoundException('No matching volume found.');
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
     * @Route("/year-{year}", name="year", requirements={"year"="[1|2][0|9][0-9][0-9]"})
     */
    public function year(int $year)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $issues = $entityManager->getRepository(Issue::class)->getIssuesByYear($year);

        if ($year < self::START_YEAR || $year > (date('Y'))) {
            return $this->createNotFoundException('No matching year found.');
        }

        return $this->render('archive/year.html.twig', [
            'issues' => $issues,
            'year' => $year,
            'previousYear' => ($year > self::START_YEAR) ? $year - 1 : false,
            'nextYear' => ($year < (int) date('Y')) ? $year + 1 : false
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
            throw $this->createNotFoundException('No matching issue found.');
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
            throw $this->createNotFoundException('No matching article found.');
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
            throw $this->createNotFoundException('No matching image found.');
        }

        try {
            $object = $s3Client->getObject([
                'Bucket' => 'pacer-archives',
                'Key'    => $image->getPath()
            ]);
        } catch (\Aws\S3\Exception\S3Exception $e) {
            throw $this->createNotFoundException('Unable to load image.');
        }

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_INLINE,
            basename($image->getPath())
        );

        $response = new Response($object['Body']);
        $response->headers->set('Content-Type', $object['ContentType']);
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    /**
     * @Route("/legacy-issue/{issueDate}", name="legacy_issue", requirements={"issueDate"="([0-9]{2,4})-([0-1][0-9])-([0-3][0-9])"})
     */
    public function legacyIssue(string $issueDate)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $issue = $entityManager->getRepository(Issue::class)->findOneBy(['issueDate' => new \DateTime($issueDate)]);

        if (!$issue) {
            throw $this->createNotFoundException('Could not locate legacy issue.');
        }

        // Redirect to new route
        return $this->redirectToRoute('issue', [
            'issueDate' => $issue->getIssueDate()->format('Y-m-d')
        ], 301);
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
            throw $this->createNotFoundException('Could not locate legacy article.');
        }

        // Redirect to new route
        return $this->redirectToRoute('article', [
            'id' => $article->getId(),
            'slug' => $article->getSlug()
        ], 301);
    }
}
