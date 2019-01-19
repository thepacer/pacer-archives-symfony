<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Entity\Article;
use App\Entity\Issue;
use App\Entity\LegacyArticle;

class ImportLegacyArticlesCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:import-legacy-articles';

    protected function configure()
    {
        $this
            ->setDescription('Imports LegacyArticle items into their normal tree')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityManager = $this->getContainer()->get('doctrine')->getEntityManager();
        $legacyArticles = $entityManager->getRepository(LegacyArticle::class)->findAll();

        $io = new SymfonyStyle($input, $output);

        foreach ($legacyArticles as $row) {
            $article = $entityManager->getRepository(Article::class)->findOneBy(['legacyId' => $row->getArticleId()]);
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

            $entityManager->persist($article);
        }

        $entityManager->flush();
    }
}
