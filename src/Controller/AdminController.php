<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    /**
     * @Route("/admin/reservations/hotels", name="app_admin_reservations_hotels")
     */
    public function show_admin_reservations_hotels (ReservationRepository $reservationRepository): Response
    {
        $reservations=$reservationRepository->findReservationsByType("hotel");
        return $this->render('admin/admin_reservations_hotels.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    /**
     * @Route("/admin/reservations/houses", name="app_admin_reservations_houses")
     */
    public function show_admin_reservations_houses (ReservationRepository $reservationRepository): Response
    {
        $reservations=$reservationRepository->findReservationsByType("house");
        return $this->render('admin/admin_reservations_houses.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    /**
     * @Route("/admin/reservations/cars", name="app_admin_reservations_cars")
     */
    public function show_admin_reservations_cars (ReservationRepository $reservationRepository): Response
    {
        $reservations=$reservationRepository->findReservationsByType("car");
        return $this->render('admin/admin_reservations_cars.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    /**
     * @Route("/admin/reservations/events", name="app_admin_reservations_events")
     */
    public function show_admin_reservations_events (ReservationRepository $reservationRepository): Response
    {
        $reservations=$reservationRepository->findReservationsByType("event");
        return $this->render('admin/admin_reservations_events.html.twig', [
            'reservations' => $reservations,
        ]);
    }
}
