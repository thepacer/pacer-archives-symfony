<?php

namespace App\Repository;

use App\Entity\Volume;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Volume|null find($id, $lockMode = null, $lockVersion = null)
 * @method Volume|null findOneBy(array $criteria, array $orderBy = null)
 * @method Volume[]    findAll()
 * @method Volume[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VolumeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Volume::class);
    }

    public function findAllCurrentVolumes()
    {
        return $this->createQueryBuilder('v')
            ->leftJoin('v.issues', 'i')
            ->addSelect('i')
            ->andWhere('v.volumeEndDate < :today')
            ->setParameter('today', new \DateTime())
            ->orderBy('v.volumeStartDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
