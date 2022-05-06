<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Voiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @method Voiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voiture[]    findAll()
 * @method Voiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoitureRepository extends ServiceEntityRepository
{


    /**
     * @var PaginationInterface
     */
    private $paginator;
    /**
     * @var FlashyNotifier
     */


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voiture::class);

    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Voiture $entity, bool $flush = true): void
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
    public function remove(Voiture $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function countNombre(){

        return $this->createQueryBuilder('v')
            ->select('count(v.id) as counts')
            ->addSelect('v.marque')
            ->groupBy('v.marque')
            ->getQuery()
            ->getResult();

    }
    public function countNombrecat(){

        return $this->createQueryBuilder('v')
            ->join('v.idCategorie','c')
            ->select('count(v.id) as counts')
            ->addSelect('c.libelle')
            ->groupBy('v.idCategorie')
            ->getQuery()
            ->getResult();

    }
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function countnbrvoiture(){

        return $this->createQueryBuilder('v')
            ->select('count(v.id) as counts')
            ->getQuery()
            ->getResult();

    }
    // /**
    //  * @return Voiture[] Returns an array of Voiture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Voiture
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
