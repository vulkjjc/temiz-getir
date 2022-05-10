<?php

namespace App\Repository;

use App\Entity\DryCleaning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DryCleaning|null find($id, $lockMode = null, $lockVersion = null)
 * @method DryCleaning|null findOneBy(array $criteria, array $orderBy = null)
 * @method DryCleaning[]    findAll()
 * @method DryCleaning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DryCleaningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DryCleaning::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(DryCleaning $entity, bool $flush = true): void
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
    public function remove(DryCleaning $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
