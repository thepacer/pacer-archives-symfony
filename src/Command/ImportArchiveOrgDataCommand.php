<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Entity\Issue;
use App\Entity\Volume;

class ImportArchiveOrgDataCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:import-archive-org-data';

    protected function configure()
    {
        $this
            ->addOption('create-volumes', null, InputOption::VALUE_NONE, 'Create volumes.')
            ->setDescription('Imports data from Archive.org')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $entityManager = $this->getContainer()->get('doctrine')->getEntityManager();

        if ($input->getOption('create-volumes')) {
            // Create 100 Years of Volumes
            $v = 0;
            do {
                $start_year = $v + 1928;
                $end_year = $start_year + 1;

                $volume = new Volume();
                $volume->setVolumeNumber($v + 1);
                $volume->setVolumeStartDate(new \DateTime("$start_year-08-01"));
                $volume->setVolumeEndDate(new \DateTime("$end_year-07-31"));
                $volume->setNameplateKey('volette');
                if ($volume->getVolumeNumber() >= 54) {
                    $volume->setNameplateKey('pacer');
                }
                $entityManager->persist($volume);
                $v++;
            } while ($v <= 99);
        }

        $entityManager->flush();

        // Import Issues
        $url = 'https://archive.org/advancedsearch.php?' . http_build_query([
            'q' => 'collection:thepacer',
            'fl' => [
                'date',
                'identifier',
                'title',
                'volume',
                'issue',
                'pages'
            ],
            'sort' => [
                'date asc'
            ],
            'rows' => 3000,
            'page' => 1,
            'output' => 'json'
        ]);

        $client = new \GuzzleHttp\Client();
        $result = $client->request('GET', $url);

        $json = json_decode($result->getBody());

        foreach ($json->response->docs as $doc) {
            // Find existing volume
            $volume = $entityManager->getRepository(Volume::class)->findOneBy([
                'volumeNumber' => (int) $doc->volume
            ]);

            if (!$volume) {
                $io->error(sprintf('No matching volume for %s', $doc->volume));
                continue;
            }

            // Find existing match based on identifer
            $issue = $entityManager->getRepository(Issue::class)->findOneBy([
                'archiveKey' => $doc->identifier
            ]);
            // Find existing match based on issue date
            $issue = $entityManager->getRepository(Issue::class)->findOneBy([
                'issueDate' => new \DateTime($doc->date)
            ]);
            if ($issue === null) {
                $issue = new Issue();
            }

            $issue->setIssueNumber($doc->issue);
            $issue->setVolume($volume);
            $issue->setIssueDate(new \DateTime($doc->date));
            $issue->setArchiveKey($doc->identifier);
            $issue->setPageCount(isset($doc->pages) ? $doc->pages : 0);
            $entityManager->persist($issue);
        }

        $entityManager->flush();

        $io->success('Done!');
    }
}
