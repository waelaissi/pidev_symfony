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
                    ->select('COUNT(r.idChambre)')
                    ->where('r.type =:type')
                    ->setParameter('type','hotel')
                    ->getQuery()
                    ->getResult();
                break;
            case "house":
                $total_booked=$this
                    ->createQueryBuilder('r')
                    ->select('COUNT(r.idMaison) ')
                    ->where('r.type =:type')
                    ->setParameter('type','maison')
                    ->getQuery()
                    ->getResult();
                break;
            case "car":
                $total_booked=$this
                    ->createQueryBuilder('r')
                    ->select('COUNT(r.idVoiture) ')
                    ->where('r.type =:type')
                    ->setParameter('type','voiture')
                    ->getQuery()
                    ->getResult();
                break;
            case "event":
                $total_booked=$this
                    ->createQueryBuilder('r')
                    ->select('COUNT(r.idTicket)')
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
    public function findTodayBooked($type)
    {
        $em=$this->getEntityManager();
        $date = new \DateTime();
        $total_booked="";
        switch ($type) {
            case "hotel":
                $total_booked=$this
                    ->createQueryBuilder('r')
                    ->select('COUNT(r.idChambre)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->setParameter('dateMin' , $date->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date->format('Y-m-d 23:59:59'))
                    ->setParameter('type','hotel')
                    ->getQuery()
                    ->getResult();
                break;
            case "house":
                $total_booked=$this
                    ->createQueryBuilder('r')
                    ->select('COUNT(r.idMaison)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->setParameter('dateMin' , $date->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date->format('Y-m-d 23:59:59'))
                    ->setParameter('type','maison')
                    ->getQuery()
                    ->getResult();
                break;
            case "car":
                $total_booked=$this
                    ->createQueryBuilder('r')
                    ->select('COUNT(r.idVoiture)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->setParameter('dateMin' , $date->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date->format('Y-m-d 23:59:59'))
                    ->setParameter('type','maison')
                    ->getQuery()
                    ->getResult();
                break;
            case "event":
                $total_booked=$this
                    ->createQueryBuilder('r')
                    ->select('COUNT(r.idTicket)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->setParameter('dateMin' , $date->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date->format('Y-m-d 23:59:59'))
                    ->setParameter('type','maison')
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
                    ->select('SUM(tr.montantPayeAvance)')
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
                    ->select('SUM(tr.montantPayeAvance) ')
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
                    ->select('SUM(tr.montantPayeAvance)')
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
                    ->select('SUM(tr.montantPayeAvance)')
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
    public function findCancled($type)
    {
        $em=$this->getEntityManager();
        $earnings="";
        switch ($type) {
            case "hotel":
                $earnings=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance) ')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('type','hotel')
                    ->setParameter('etat','annulée')
                    ->getQuery()
                    ->getResult();
                break;
            case "house":
                $earnings=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance) ')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('type','maison')
                    ->setParameter('etat','annulée')
                    ->getQuery()
                    ->getResult();
                break;
            case "car":
                $earnings=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance) ')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('type','voiture')
                    ->setParameter('etat','annulée')
                    ->getQuery()
                    ->getResult();
                break;
            case "event":
                $earnings=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance) ')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('type','evenement')
                    ->setParameter('etat','annulée')
                    ->getQuery()
                    ->getResult();
                break;
            default :
                break;
        }
        return $earnings;
    }


    public function findGainsYearly($type,$year)
    {
        $em=$this->getEntityManager();

        $date_min_string=$year."-01-01";
        $date_min= new \DateTime($date_min_string);
        $date_max_string=$year."-12-30";
        $date_max= new \DateTime($date_max_string);
        $total="";
        switch ($type) {
            case "hotel":
                $total=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('dateMin' , $date_min->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date_max->format('Y-m-d 23:59:59'))
                    ->setParameter('etat','confirmée')
                    ->setParameter('type','hotel')
                    ->getQuery()
                    ->getResult();
                break;
            case "house":
                $total=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('dateMin' , $date_min->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date_max->format('Y-m-d 23:59:59'))
                    ->setParameter('etat','confirmée')
                    ->setParameter('type','maison')
                    ->getQuery()
                    ->getResult();
                break;
            case "car":
                $total=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('dateMin' , $date_min->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date_max->format('Y-m-d 23:59:59'))
                    ->setParameter('etat','confirmée')
                    ->setParameter('type','voiture')
                    ->getQuery()
                    ->getResult();
                break;
            case "event":
                $total=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('dateMin' , $date_min->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date_max->format('Y-m-d 23:59:59'))
                    ->setParameter('etat','confirmée')
                    ->setParameter('type','evenement')
                    ->getQuery()
                    ->getResult();
                break;

        }
        return $total;
    }
    public function findGainsMonthly($type,$year,$month)
    {
        $em=$this->getEntityManager();

        $date_min_string=$year."-".$month."-01";
        $date_min= new \DateTime($date_min_string);
        $date_max_string=$year."-".$month."-31";
        $date_max= new \DateTime($date_max_string);
        $total="";
        switch ($type) {
            case "hotel":
                $total=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('dateMin' , $date_min->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date_max->format('Y-m-d 23:59:59'))
                    ->setParameter('etat','confirmée')
                    ->setParameter('type','hotel')
                    ->getQuery()
                    ->getResult();
                break;
            case "house":
                $total=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('dateMin' , $date_min->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date_max->format('Y-m-d 23:59:59'))
                    ->setParameter('etat','confirmée')
                    ->setParameter('type','maison')
                    ->getQuery()
                    ->getResult();
                break;
            case "car":
                $total=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('dateMin' , $date_min->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date_max->format('Y-m-d 23:59:59'))
                    ->setParameter('etat','confirmée')
                    ->setParameter('type','voiture')
                    ->getQuery()
                    ->getResult();
                break;
            case "event":
                $total=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('dateMin' , $date_min->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date_max->format('Y-m-d 23:59:59'))
                    ->setParameter('etat','confirmée')
                    ->setParameter('type','evenement')
                    ->getQuery()
                    ->getResult();
                break;

        }
        return $total;
    }
    public function findLossYearly($type,$year)
    {
        $em=$this->getEntityManager();

        $date_min_string=$year."-01-01";
        $date_min= new \DateTime($date_min_string);
        $date_max_string=$year."-12-30";
        $date_max= new \DateTime($date_max_string);
        $total="";
        switch ($type) {
            case "hotel":
                $total=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('dateMin' , $date_min->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date_max->format('Y-m-d 23:59:59'))
                    ->setParameter('etat','annulée')
                    ->setParameter('type','hotel')
                    ->getQuery()
                    ->getResult();
                break;
            case "house":
                $total=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('dateMin' , $date_min->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date_max->format('Y-m-d 23:59:59'))
                    ->setParameter('etat','annulée')
                    ->setParameter('type','maison')
                    ->getQuery()
                    ->getResult();
                break;
            case "car":
                $total=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('dateMin' , $date_min->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date_max->format('Y-m-d 23:59:59'))
                    ->setParameter('etat','annulée')
                    ->setParameter('type','voiture')
                    ->getQuery()
                    ->getResult();
                break;
            case "event":
                $total=$this
                    ->createQueryBuilder('r')
                    ->select('SUM(tr.montantPayeAvance)')
                    ->join('r.idTransaction','tr')
                    ->where('r.type =:type')
                    ->andWhere('tr.createdAt BETWEEN :dateMin AND :dateMax')
                    ->andWhere('r.etat=:etat')
                    ->setParameter('dateMin' , $date_min->format('Y-m-d 00:00:00'))
                    ->setParameter('dateMax' , $date_max->format('Y-m-d 23:59:59'))
                    ->setParameter('etat','annulée')
                    ->setParameter('type','evenement')
                    ->getQuery()
                    ->getResult();
                break;

        }
        return $total;
    }












}