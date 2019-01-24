<?php

namespace App\Repository;

use App\Entity\Issue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Issue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Issue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Issue[]    findAll()
 * @method Issue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IssueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Issue::class);
    }

    public function countIssues()
    {
        return $this->createQueryBuilder('i')
            ->select('COUNT(i.id) as issueCount')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findPreviousIssue(Issue $issue)
    {
        return $this->createQueryBuilder('i')
            ->where('i.issueDate < :issueDate')
            ->setParameter('issueDate', $issue->getIssueDate())
            ->orderBy('i.issueDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findNextIssue(Issue $issue)
    {
        return $this->createQueryBuilder('i')
            ->where('i.issueDate > :issueDate')
            ->setParameter('issueDate', $issue->getIssueDate())
            ->orderBy('i.issueDate', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
