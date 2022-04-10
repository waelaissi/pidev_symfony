<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use http\Env\Request;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="app_reservation")
     */
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    /**
     * @Route("/reservation/create", name="app_reservation")
     */
    public function create_reservation(EntityManagerInterface $em): Response
    {
        $reservation=new Reservation();
        return $this->render('reservation/reservations_client.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
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
     * @Route("/reservation/get/{type}/{id_user}", name="app_reservation_get_client")
     */
    public function show_reservations_client($type,$id_user, ReservationRepository $reservationRepository)
    {
        $reservations=$reservationRepository->findClientReservationsByType($type,$id_user);
        return $this->render('reservation/reservations_client.html.twig', [
            'reservations' => $reservations,

        ]);
    }




}



