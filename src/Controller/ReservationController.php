<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Entity\Reservation;
use App\Repository\ChambreRepository;
use App\Repository\EvenementRepository;
use App\Repository\HotelRepository;
use App\Repository\MaisonRepository;
use App\Repository\ReservationRepository;
use App\Repository\TicketRepository;
use App\Repository\TransactionRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ObjectManager;
use MercurySeries\FlashyBundle\FlashyNotifier;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ReservationController extends AbstractController
{

    /**
     * @Route("/reservation/create/{type}/{id_type}/{id_user}/{id_transaction}/{date_arrive}/{date_depart}/{total}", name="app_reservation_create")
     */
    public function create_reservation($type, $id_type, $id_user,$id_transaction, $date_arrive,$date_depart,$total, Request $request, UtilisateurRepository $utilisateurRepository,
                                       TransactionRepository $transactionRepository, ChambreRepository $chambreRepository,
                                       TicketRepository $ticketRepository, VoitureRepository $voitureRepository, MaisonRepository $maisonRepository,
                                       EntityManagerInterface $entityManager): Response
    {
        $transaction=$transactionRepository->find($id_transaction);
        $reservation=new Reservation();
        $reservation->setIdUser($utilisateurRepository->find($id_user));
        $reservation->setIdTransaction($transaction);
        $reservation->setMontantAPayer($total);
        $reservation->setResteAPayer($total-($transaction->getMontantPayeAvance()));
        switch ($type) {
            case "hotel":
                $reservation->setDateDebut(new \DateTime($date_arrive));
                $reservation->setDateFin(new \DateTime($date_depart));
                $chambre=$chambreRepository->find($id_type);
                $reservation->setIdChambre($chambre);
                $reservation->setType("hotel");
                break;
            case "house":
                $reservation->setDateDebut(new \DateTime($date_arrive));
                $reservation->setDateFin(new \DateTime($date_depart));
                $house=$maisonRepository->find($id_type);
                $reservation->setIdMaison($house);
                $reservation->setType("maison");
                break;
            case "car":
                $reservation->setDateDebut(new \DateTime($date_arrive));
                $reservation->setDateFin(new \DateTime($date_depart));
                $voiture=$voitureRepository->find($id_type);
                $reservation->setIdVoiture($voiture);
                $reservation->setType("voiture");
                break;
            case "event":
                $reservation->setDateDebut(new \DateTime($date_arrive));
                $reservation->setDateFin(new \DateTime($date_arrive));
                $ticket=$ticketRepository->find($id_type);
                $reservation->setIdTicket($ticket);
                $reservation->setType("evenement");
                break;
        }
        $entityManager->persist($reservation);
        $entityManager->flush();


        return $this->redirectToRoute('app_transaction_confirmation',['id_user'=>$id_user,'id_transaction'=>$transaction->getId()]);

    }
    /**
     * @Route("/reservation/cancel/{id}/{type}/{id_user}", name="app_reservation_cancel")
     */
    public function cancel_reservation($id,$type,$id_user,ReservationRepository $reservationRepository)
    {
        $result=$reservationRepository->cancel_reservation($id);
        var_dump($result);
        return $this->redirectToRoute('app_reservation_get_client',['type'=>$type,'id_user'=>$id_user]);

    }
    /**
     * @Route("/reservation/cancel/{id}/{type}", name="app_reservation_admin_cancel")
     */
    public function cancel_reservation_admin($id,$type,ReservationRepository $reservationRepository)
    {
        $result=$reservationRepository->cancel_reservation($id);
        switch ($type) {
            case "hotel":
                return $this->redirectToRoute('app_admin_reservations_hotels');
            case "house":
                return $this->redirectToRoute('app_admin_reservations_houses');
            case "car":
                return $this->redirectToRoute('app_admin_reservations_cars');
            case "event":
                return $this->redirectToRoute('app_admin_reservations_events');
        }
        return $this->redirectToRoute('app_admin_reservations_hotels');
    }

    /**
     * @Route("/reservation/delete/{id}/{type}", name="app_reservation_delete")
     */
    public function delete_reservation($id,$type,ReservationRepository $reservationRepository,EntityManagerInterface $entityManager)
    {
        $reservation=$reservationRepository->find($id);
        $entityManager->remove($reservation);
        $entityManager->flush();
        switch ($type) {
            case "hotel":
                return $this->redirectToRoute('app_admin_reservations_hotels');
            case "house":
                return $this->redirectToRoute('app_admin_reservations_houses');
            case "car":
                return $this->redirectToRoute('app_admin_reservations_cars');
            case "event":
                return $this->redirectToRoute('app_admin_reservations_events');
        }
        return $this->redirectToRoute('app_admin_reservations_hotels');
    }


    /**
     * @Route("/reservation/get/{type}/{id_user}", name="app_reservation_get_client")
     */
    public function show_reservations_client($type,$id_user, ReservationRepository $reservationRepository)
    {
        $reservations=$reservationRepository->findClientReservationsByType($type,$id_user);
        return $this->render('reservation/reservations_client.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * @Route("/stats/hotels", name="app_hotels_available")
     */
    public function get_stats_hotels(ReservationRepository $reservationRepository)
    {
        //$data=$reservationRepository->findTotalBooked("car");
        $data=$reservationRepository->findEarnings("hotel");
         return new Response(json_encode($data));
    }












}



