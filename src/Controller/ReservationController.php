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
use App\Service\Mail;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ObjectManager;
use Dompdf\Dompdf;
use MercurySeries\FlashyBundle\FlashyNotifier;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
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
                                       EntityManagerInterface $entityManager,MailerInterface $mailer,Mail $mail): Response
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
                $mail->send_email($reservation->getIdUser()->getEmail(),$mail->reservation_mail_subject("hotel"),
                                    $mail->reservation_mail_content("hotel",$chambre->getIdHotel()->getLibelle()),$mailer);

                break;
            case "house":
                $reservation->setDateDebut(new \DateTime($date_arrive));
                $reservation->setDateFin(new \DateTime($date_depart));
                $house=$maisonRepository->find($id_type);
                $reservation->setIdMaison($house);
                $reservation->setType("maison");
                $mail->send_email($reservation->getIdUser()->getEmail(),$mail->reservation_mail_subject("house"),
                    $mail->reservation_mail_content("house",$house->getAdresse()),$mailer);
                break;
            case "car":
                $reservation->setDateDebut(new \DateTime($date_arrive));
                $reservation->setDateFin(new \DateTime($date_depart));
                $voiture=$voitureRepository->find($id_type);
                $reservation->setIdVoiture($voiture);
                $reservation->setType("voiture");
                $mail->send_email($reservation->getIdUser()->getEmail(),$mail->reservation_mail_subject("car"),
                    $mail->reservation_mail_content("car",$voiture->getMarque().' , model '.$voiture->getModel()),$mailer);
                break;
            case "event":
                $reservation->setDateDebut(new \DateTime($date_arrive));
                $reservation->setDateFin(new \DateTime($date_arrive));
                $ticket=$ticketRepository->find($id_type);
                $reservation->setIdTicket($ticket);
                $reservation->setType("evenement");
                $mail->send_email($reservation->getIdUser()->getEmail(),$mail->reservation_mail_subject("event"),
                    $mail->reservation_mail_content("event",$ticket->getIdEvenement()->getLibelle()." ".$ticket->getType()." ticket "),$mailer);
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
        $transaction=$reservationRepository->find($id)->getIdTransaction();
        $result=$reservationRepository->cancel_reservation($id);
        return $this->redirectToRoute('app_stripe_refund',['type'=>$type,'id_user'=>$id_user,'id_transaction'=>$transaction->getId(),'action'=>'client']);

    }
    /**
     * @Route("/reservation/cancel/{id}/{type}", name="app_reservation_admin_cancel")
     */
    public function cancel_reservation_admin($id,$type,ReservationRepository $reservationRepository)
    {
        $transaction=$reservationRepository->find($id)->getIdTransaction();
        $id_user=$reservationRepository->find($id)->getIdUser()->getId();
        $result=$reservationRepository->cancel_reservation($id);
        return $this->redirectToRoute('app_stripe_refund',['type'=>$type,'id_user'=>$id_user,'id_transaction'=>$transaction->getId(),'action'=>'admin']);

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
     * @Route("/print/receipt/{type}/{id_type}/{id_user}", name="app_print_recipt")
     */
    public function print_recipt($type,$id_type,$id_user,UtilisateurRepository $utilisateurRepository,ReservationRepository $reservationRepository)
    {
        $dompdf = new Dompdf();
        $user=$utilisateurRepository->find($id_user);
        $reservtion=$reservationRepository->find($id_type);
        switch ($type) {
            case "hotel":
                $html=$this->renderView('Recipt/Recipt_hotel.html.twig', [
                    'user'=>$user,
                    'reservation' => $reservtion,
                ]);
                $dompdf->loadHtml($html);
                break;

            case "maison":
                $html=$this->renderView('Recipt/Recipt_house.html.twig', [
                    'user'=>$user,
                    'reservation' => $reservtion,
                ]);
                $dompdf->loadHtml($html);
                break;

            case "voiture":
                $html=$this->renderView('Recipt/Recipt_car.html.twig', [
                    'user'=>$user,
                    'reservation' => $reservtion,
                ]);
                $dompdf->loadHtml($html);
                break;

            case "evenement":
                $html=$this->renderView('Recipt/Recipt_event.html.twig', [
                    'user'=>$user,
                    'reservation' => $reservtion,
                ]);
                $dompdf->loadHtml($html);
                break;
        }

        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Reservation.pdf",["Attachment"=>false]);
    }

    //test
    /**
     * @Route("/stats/hotels", name="dd")
     */
    public function get_stats_hotels(ReservationRepository $reservationRepository)
    {
        //$data=$reservationRepository->findTotalBooked("car");
        //$data=$reservationRepository->findEarnings("hotel");
        //$data=$reservationRepository->findEarnings("hotel");
        $date_today = new \DateTime();
        $this_year=$date_today->format("Y");
        $months=['01','02','03','04','05','06','07','08','09','10','11','12'];
        $array_stats_monthly=[];
        $i=1;
        foreach($months as $value){
            $data=$reservationRepository->findGainsMonthly("hotel",$this_year,$value)[0][1] | 0;
            array_push($array_stats_monthly,[$value=>$data]);
        }
        /*$data=$reservationRepository->findGainsYearly("hotel",$this_year)[0][1] | 0 ;
        $data1=$reservationRepository->findGainsYearly("hotel",$this_year-1)[0][1] | 0;
        $data2=$reservationRepository->findGainsYearly("hotel",$this_year-2)[0][1] | 0;
        $array_stats_annually =[$this_year=>$data,$this_year-1=>$data1,$this_year-2=>$data2];*/
        return new Response(json_encode($array_stats_monthly));
    }












}



