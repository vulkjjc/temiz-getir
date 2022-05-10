<?php

namespace App\Repository;

use App\Entity\ShoeCleaning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShoeCleaning|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoeCleaning|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoeCleaning[]    findAll()
 * @method ShoeCleaning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoeCleaningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoeCleaning::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ShoeCleaning $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ShoeCleaning $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
