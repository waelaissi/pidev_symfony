<?php

namespace App\Repository;

use App\Entity\Hotel;
use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\HotelRepository;

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



    public function findReservationsByType($type)
    {
        $query="";
        switch ($type) {
            case "hotel":
                $query=$this->createQueryBuilder('r')
                    ->Where('r.type = :type ')
                    ->join("r.idTransaction",'t')
                    ->orderBy('t.createdAt','DESC')
                    ->setParameter('type','hotel')
                    ->getQuery()
                    ->getResult();
                break;
            case "house":
                $query=$this->createQueryBuilder('r')
                    ->Where('r.type = :type ')
                    ->join("r.idTransaction",'t')
                    ->orderBy('t.createdAt','DESC')
                    ->setParameter('type','maison')
                    ->getQuery()
                    ->getResult();
                break;
            case "car":
                $query=$this->createQueryBuilder('r')
                    ->Where('r.type = :type ')
                    ->join("r.idTransaction",'t')
                    ->orderBy('t.createdAt','DESC')
                    ->setParameter('type','voiture')
                    ->getQuery()
                    ->getResult();
                break;
            case "event":
                $query=$this->createQueryBuilder('r')
                    ->Where('r.type = :type ')
                    ->join("r.idTransaction",'t')
                    ->orderBy('t.createdAt','DESC')
                    ->setParameter('type',"evenement")
                    ->getQuery()
                    ->getResult();
                break;
            default :
                $query=$this->createQueryBuilder('r')
                    ->join("r.idTransaction",'t')
                    ->orderBy('t.createdAt','DESC')
                    ->getQuery()
                    ->getResult();
        }
        return$query;
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
    public function findAvailableHotels($date_debut,$date_fin,$region,HotelRepository $hotelRepository)
    {
        $em=$this->getEntityManager();
        $rooms_not_available=$this
            ->createQueryBuilder('r')
            ->select('distinct(r.idChambre)')
            ->where('r.dateDebut BETWEEN :date_debut and :date_fin')
            ->orWhere('r.dateFin BETWEEN :date_debut and :date_fin')
            ->orWhere('r.dateDebut < :date_debut AND r.dateFin > :date_fin')
            ->andWhere('r.type = :type')
            ->setParameter('date_debut',$date_debut)
            ->setParameter('date_fin',$date_fin)
            ->setParameter('type','hotel')
            ->getQuery()
            ->getResult();
        $hotels_id_available=$em
            ->createQueryBuilder()
            ->select('distinct(ht.id) as id_hotel')
            ->from('App\Entity\Chambre','ch')
            ->where('ch.id not in ( :data )')
            ->join('ch.idHotel','ht')
            ->setParameter('data',$rooms_not_available)
            ->getQuery()
            ->getResult();
        return $hotelRepository->findByList($hotels_id_available,$region);
    }
    public function findAvailableRooms($date_debut,$date_fin,$id_hotel,ChambreRepository $chambreRepository)
    {
        $em=$this->getEntityManager();
        $rooms_not_available=$this
            ->createQueryBuilder('r')
            ->select('distinct(r.idChambre)')
            ->where('r.dateDebut BETWEEN :date_debut and :date_fin')
            ->orWhere('r.dateFin BETWEEN :date_debut and :date_fin')
            ->orWhere('r.dateDebut < :date_debut AND r.dateFin > :date_fin')
            ->andWhere('r.type = :type')
            ->setParameter('date_debut',$date_debut)
            ->setParameter('date_fin',$date_fin)
            ->setParameter('type','hotel')
            ->getQuery()
            ->getResult();
        return $chambreRepository->findByList($rooms_not_available,$id_hotel);
    }
    public function findAvailableHouses($date_debut,$date_fin,$region,MaisonRepository $maisonRepository)
    {
        $em=$this->getEntityManager();
        $houses_not_available=$this
            ->createQueryBuilder('r')
            ->select('distinct(r.idMaison)')
            ->where('r.dateDebut BETWEEN :date_debut and :date_fin')
            ->orWhere('r.dateFin BETWEEN :date_debut and :date_fin')
            ->orWhere('r.dateDebut < :date_debut AND r.dateFin > :date_fin')
            ->andWhere('r.type = :type')
            ->setParameter('date_debut',$date_debut)
            ->setParameter('date_fin',$date_fin)
            ->setParameter('type','maison')
            ->getQuery()
            ->getResult();
        return $maisonRepository->findByList($houses_not_available,$region);
    }

    public function findAvailableCars($date_debut,$date_fin,$region,VoitureRepository $voitureRepository)
    {
        $em=$this->getEntityManager();
        $cars_not_available=$this
            ->createQueryBuilder('r')
            ->select('distinct(r.idVoiture)')
            ->where('r.dateDebut BETWEEN :date_debut and :date_fin')
            ->orWhere('r.dateFin BETWEEN :date_debut and :date_fin')
            ->orWhere('r.dateDebut < :date_debut AND r.dateFin > :date_fin')
            ->andWhere('r.type = :type')
            ->setParameter('date_debut',$date_debut)
            ->setParameter('date_fin',$date_fin)
            ->setParameter('type','voiture')
            ->getQuery()
            ->getResult();
         return $voitureRepository->findByList($cars_not_available,$region);
    }

    public function findAvailableEvents($date_debut,$region,EvenementRepository $evenementRepository)
    {
        return $evenementRepository->findAvailableEvents($date_debut,$region);
    }

    public function findTotalBooked($type)
    {
        $em=$this->getEntityManager();
        $total_booked="";

        switch ($type) {
            case "hotel":
                $total_booked=$this
                    ->createQueryBuilder('r')
                    ->select('COUNT(r.idChambre) as nbr_reservation_hotels')
                    ->where('r.type =:type')
                    ->setParameter('type','hotel')
                    ->getQuery()
                    ->getResult();
                break;
            case "house":
                $total_booked=$this
                    ->createQueryBuilder('r')
                    ->select('COUNT(r.idMaison) as nbr_reservation_houses')
                    ->where('r.type =:type')
                    ->setParameter('type','maison')
                    ->getQuery()
                    ->getResult();
                break;
            case "car":
                $total_booked=$this
                    ->createQueryBuilder('r')
                    ->select('COUNT(r.idVoiture) as nbr_reservation_cars')
                    ->where('r.type =:type')
                    ->setParameter('type','voiture')
                    ->getQuery()
                    ->getResult();
                break;
            case "event":
                $total_booked=$this
                    ->createQueryBuilder('r')
                    ->select('COUNT(r.idEvenement) as nbr_reservation_events')
                    ->where('r.type =:type')
                    ->setParameter('type','evenement')
                    ->getQuery()
                    ->getResult();
                break;
            default :
               break;
        }
        return $total_booked;
    }

    public function findEarnings($type)
    {
        $em=$this->getEntityManager();
        $earnings="";
        switch ($type) {
            case "hotel":
                $earnings=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance) as gains_reservation_hotels')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('type','hotel')
                    ->setParameter('etat','confirmée')
                    ->getQuery()
                    ->getResult();
                break;
            case "house":
                $earnings=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance) as gains_reservation_houses')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('type','maison')
                    ->setParameter('etat','confirmée')
                    ->getQuery()
                    ->getResult();
                break;
            case "car":
                $earnings=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance) as gains_reservation_cars')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('type','voiture')
                    ->setParameter('etat','confirmée')
                    ->getQuery()
                    ->getResult();
                break;
            case "event":
                $earnings=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance) as gains_reservation_events')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('type','evenement')
                    ->setParameter('etat','confirmée')
                    ->getQuery()
                    ->getResult();
                break;
            default :
                break;
        }
        return $earnings;
    }










}