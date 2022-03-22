<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Maison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Maison|null find($id, $lockMode = null, $lockVersion = null)
 * @method Maison|null findOneBy(array $criteria, array $orderBy = null)
 * @method Maison[]    findAll()
 * @method Maison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaisonRepository extends ServiceEntityRepository
{
    /**
     * @var PaginationInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry,  PaginatorInterface $paginator)
    {
        parent::__construct($registry, Maison::class);
        $this->paginator = $paginator;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Maison $entity, bool $flush = true): void
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
    public function remove(Maison $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Récupère les produits en lien avec une recherche
     * @return PaginationInterface
     */

    public function findSearch(SearchData $search): PaginationInterface
    {


        $query = $this->getSearchQuery($search)->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            6
        );
    }


    /**
     * Récupère le prix minimum et maximum correspondant à une recherche
     * @return integer[]
     */
    public function findMinMax(SearchData $search): array
    {
        $results = $this->getSearchQuery($search, true)
            ->select('MIN(m.prix) as min', 'MAX(m.prix) as max')
            ->getQuery()
            ->getScalarResult();
        return [(int)$results[0]['min'], (int)$results[0]['max']];
    }

    private function getSearchQuery(SearchData $search, $ignorePrice = false): QueryBuilder
    {
        $query = $this
            ->createQueryBuilder('m')
            ->select('m');

        ;

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('m.region LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->reg)) {
            $query = $query
                ->andWhere('m.region = :reg')
                ->setParameter('reg', "%{$search->reg}%");
        }

        if (!empty($search->min) && ($ignorePrice === false)) {
            $query = $query
                ->andWhere('m.prix >= :min')
                ->setParameter('min', $search->min);
        }

        if (!empty($search->max) && ($ignorePrice === false)) {
            $query = $query
                ->andWhere('m.prix <= :max')
                ->setParameter('max', $search->max);
        }
        return $query;

    }

    // /**
    //  * @return Maison[] Returns an array of Maison objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Maison
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
