<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Data\SearchHotelData;
use App\Entity\Hotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Hotel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hotel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hotel[]    findAll()
 * @method Hotel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelRepository extends ServiceEntityRepository
{

    /**
     * @var PaginationInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Hotel::class);
        $this->paginator = $paginator;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Hotel $entity, bool $flush = true): void
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
    public function remove(Hotel $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Récupère les hotels en lien avec une recherche
     * @return PaginationInterface
     */

    public function findSearch(SearchHotelData $search): PaginationInterface
    {
        $query = $this->getSearchQuery($search)->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            6
        );
    }

    private function getSearchQuery(SearchHotelData $search): QueryBuilder
    {
        $query = $this
            ->createQueryBuilder('h')
            ->select('h');

        ;

        if (!empty($search->libelle)) {
            $query = $query
                ->andWhere('h.libelle LIKE :l')
                ->setParameter('l', "%{$search->libelle}%");
        }

        if (!empty($search->region)) {
            $query = $query
                ->andWhere('h.region LIKE :reg')
                ->setParameter('reg', "%{$search->region}%");
        }



        if (!empty($search->ville)) {
            $query = $query
                ->andWhere('h.ville LIKE :v')
                ->setParameter('v', "%{$search->ville}%");
        }

        if (!empty($search->nbEtoile)) {
            $query = $query
                ->andWhere('h.nbEtoiles = :nbEtoile')
                ->setParameter('nbEtoile', $search->nbEtoile);
        }
        return $query;

    }

    // /**
    //  * @return Hotel[] Returns an array of Hotel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hotel
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
