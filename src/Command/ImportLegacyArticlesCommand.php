<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Entity\Article;
use App\Entity\Image;
use App\Entity\Issue;
use App\Entity\LegacyArticle;

class ImportLegacyArticlesCommand extends Command
{
    const BATCH_SIZE = 1000;

    protected static $defaultName = 'app:import-legacy-articles';

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Imports LegacyArticle items into Article items')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $legacyArticles = $this->entityManager->getRepository(LegacyArticle::class)->findAll();

        $io = new SymfonyStyle($input, $output);

        foreach ($legacyArticles as $i => $row) {
            $article = $this->entityManager->getRepository(Article::class)->findOneBy(['legacyId' => $row->getArticleId()]);
            if ($article === null) {
                $article = new Article();
            }
            $article->setLegacyId($row->getArticleId());
            $article->setPrintSection($row->getSectionId());
            $article->setHeadline((string )$row->getTitle());
            $article->setAlternativeHeadline((string) $row->getSubtitle());
            if ($row->getAuthorTitle()) {
                $author_byline = $row->getAuthorId() . ', ' . $row->getAuthorTitle();
            } else {
                $author_byline = (string) $row->getAuthorId();
            }
            $article->setAuthorByline($author_byline);
            if ($row->getCoAuthorTitle()) {
                $contributor_byline = $row->getCoAuthorId() . ', ' . $row->getCoAuthorTitle();
            } else {
                $contributor_byline = (string) $row->getCoAuthorId();
            }
            $article->setContributorByline($contributor_byline);
            $article->setArticleBody($row->getFullText());
            $article->setDateCreated(new \DateTime($row->getIssueId()));
            $article->setDatePublished(new \DateTime($row->getIssueId()));
            $modified = explode(' by ', $row->getLastEdited());
            $article->setDateModified(new \DateTime($modified[0]));
            $article->setModifiedBy($modified[1]);
            $article->setKeywords((string) $row->getKeywords());
            $issue = $entityManager->getRepository(Issue::class)->findOneBy([
                'issueDate' => new \DateTime($row->getIssueId())
            ]);
            $article->setIssue($issue);

            // Handle attached Images
            if ($row->getPhotoSrc()) {
                if ($article->getImages() && $article->getImages()->first()) {
                    $image = $article->getImages()->first();
                    $image->setPath($row->getPhotoSrc());
                    $image->setCredit((string) $row->getPhotoCredit());
                    $image->setCaption((string) $row->getPhotoCaption());
                } else {
                    $image = new Image();
                    $image->setPath($row->getPhotoSrc());
                    $image->setCredit((string) $row->getPhotoCredit());
                    $image->setCaption((string) $row->getPhotoCaption());
                    $article->addImage($image);
                }
            }

            $this->entityManager->persist($article);

            // Batch inserts
            if (($i%self::BATCH_SIZE) === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }

        }

        // Catch the rest of them
        $entityManager->flush();
        $entityManager->clear();
    }
}
