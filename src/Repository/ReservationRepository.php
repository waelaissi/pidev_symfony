<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Reservation $entity, bool $flush = true): void
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
    public function remove(Reservation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function create_reservation(Reservation $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    public function cancel_reservation($id)

    {
        $em=$this->getEntityManager();
        return $em->createQuery("Update App\Entity\Reservation r set r.etat = :etat where r.id = :id")
            ->setParameter('etat',"annulée")
            ->setParameter('id',$id)
            ->getResult();
    }




    public function findClientReservationsByType($type,$id_user)
    {
        $query="";
        switch ($type) {
            case "hotel":
                $query=$this->createQueryBuilder('r')
                    ->where('r.idUser = :id_user')
                    ->andWhere('r.type = :type ')
                    ->setParameter('id_user',$id_user)
                    ->setParameter('type','hotel')
                    ->getQuery()
                    ->getResult();
                break;
            case "house":
                $query=$this->createQueryBuilder('r')
                    ->where('r.idUser = :id_user')
                    ->andWhere('r.type = :type ')
                    ->setParameter('id_user',$id_user)
                    ->setParameter('type','voiture')
                    ->getQuery()
                    ->getResult();
                break;
            case "car":
                $query=$this->createQueryBuilder('r')
                    ->where('r.idUser = :id_user')
                    ->andWhere('r.type = :type ')
                    ->setParameter('id_user',$id_user)
                    ->setParameter('type','maison')
                    ->getQuery()
                    ->getResult();
                break;
            case "event":
                $query=$this->createQueryBuilder('r')
                    ->where('r.idUser = :id_user')
                    ->andWhere('r.type = :type ')
                    ->setParameter('id_user',$id_user)
                    ->setParameter('type',"evenement")
                    ->getQuery()
                    ->getResult();
                break;
            default :
                $query=$this->createQueryBuilder('r')
                    ->join("r.idTransaction",'t')
                    ->orderBy('t.createdAt','DESC')
                    ->where('r.idUser = :id_user')
                    ->setParameter('id_user',$id_user)
                    ->getQuery()
                    ->getResult();
        }
        return$query;
    }



}