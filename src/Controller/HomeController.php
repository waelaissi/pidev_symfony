<?php

namespace App\Controller;

use App\Repository\ChambreRepository;
use App\Repository\EvenementRepository;
use App\Repository\HotelRepository;
use App\Repository\MaisonRepository;
use App\Repository\ReservationRepository;
use App\Repository\TicketRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\VoitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/hotels", name="app_hotels")
     */
    public function show_hotels(HotelRepository $hotelRepository): Response
    {
        $hotels=$hotelRepository->findAll();
        return $this->render('home/hotels.html.twig', [
            'hotels' => $hotels,
            'date_arrive' => '',
        ]);
    }

    /**
     * @Route("/rooms/{id_hotel}/{date_arrive}/{date_depart}", name="app_hotel_rooms")
     */
    public function show_hotel_rooms($id_hotel,$date_arrive,$date_depart,HotelRepository $hotelRepository): Response
    {
        $hotel=$hotelRepository->find($id_hotel);
        return $this->render('home/rooms.html.twig', [
            'hotel'=>$hotel,
            'date_depart'=>$date_depart,
            'date_arrive' => $date_arrive,
        ]);
    }
    /**
     * @Route("/room/detail/{id_room}/{id_hotel}/{id_user}/{date_arrive}/{date_depart}", name="app_room_details")
     */
    public function show_room_details($id_room,$id_hotel,$id_user,UtilisateurRepository $utilisateurRepository,
                                      HotelRepository $hotelRepository,ChambreRepository $chambreRepository,
        $date_arrive,$date_depart): Response
    {
        $room=$chambreRepository->find($id_room);
        $hotel=$hotelRepository->find($id_hotel);
        $user=$utilisateurRepository->find($id_user);

        return $this->render('home/room_details.html.twig', [
            'user' => $user,
            'room'=>$room,
            'hotel'=>$hotel,
            'date_depart'=>$date_depart,
            'date_arrive' => $date_arrive,
        ]);
    }
    /**
     * @Route("/houses", name="app_houses")
     */
    public function show_houses(MaisonRepository $maisonRepository): Response
    {
        $houses=$maisonRepository->findAll();
        return $this->render('home/houses.html.twig', [
            'houses' => $houses,
            'date_arrive' => '',
        ]);
    }
    /**
     * @Route("/house/detail/{id_house}/{id_user}/{date_arrive}/{date_depart}", name="app_house_details")
     */
    public function show_house_details($id_house,$id_user,UtilisateurRepository $utilisateurRepository,
                                     MaisonRepository $houseRepository, $date_arrive,$date_depart): Response
    {
        $house=$houseRepository->find($id_house);
        $user=$utilisateurRepository->find($id_user);

        return $this->render('home/house_details.html.twig', [
            'user' => $user,
            'house'=>$house,
            'date_depart'=>$date_depart,
            'date_arrive' => $date_arrive,
        ]);
    }

    /**
     * @Route("/cars", name="app_cars")
     */
    public function show_cars(VoitureRepository $voitureRepository): Response
    {
        $cars=$voitureRepository->findAll();
        return $this->render('home/cars.html.twig', [
            'cars' => $cars,
            'date_arrive' => '',
        ]);
    }



    /**
     * @Route("/car/detail/{id_car}/{id_user}/{date_arrive}/{date_depart}", name="app_car_details")
     */
    public function show_car_details($id_car,$id_user,UtilisateurRepository $utilisateurRepository,
                                      VoitureRepository $voitureRepository,
        $date_arrive,$date_depart): Response
    {
        $car=$voitureRepository->find($id_car);
        $user=$utilisateurRepository->find($id_user);

        return $this->render('home/car_details.html.twig', [
            'user' => $user,
            'car'=>$car,
            'date_depart'=>$date_depart,
            'date_arrive' => $date_arrive,
        ]);
    }

    /**
     * @Route("/events", name="app_events")
     */
    public function show_events(EvenementRepository $evenementRepository): Response
    {
        $events=$evenementRepository->findAll();
        return $this->render('home/events.html.twig', [
            'events' => $events,
            'date_arrive' => '',
        ]);
    }
    /**
     * @Route("/event/detail/{id_event}/{id_user}/{date_arrive}", name="app_event_details")
     */
    public function show_event_details($id_event,$id_user,UtilisateurRepository $utilisateurRepository,TicketRepository $ticketRepository,
                                     EvenementRepository $evenementRepository,$date_arrive,): Response
    {
        $tickets=$ticketRepository->findBy(array('idEvenement' => $id_event),array('type' => 'DESC'),2 ,0);
        $event=$evenementRepository->find($id_event);
        $user=$utilisateurRepository->find($id_user);

        return $this->render('home/event_details.html.twig', [
            'user' => $user,
            'event'=>$event,
            'tickets'=>$tickets,
            'date_arrive' => $date_arrive,
        ]);
    }


    /**
     * @Route("/hotels/available/{date_debut}/{date_fin}/{region}", name="app_hotels_available")
     */
    public function get_available_hotels($date_debut,$date_fin,$region,ReservationRepository $reservationRepository,
                                         HotelRepository $hotelRepository)
    {
        $hotels=$reservationRepository->findAvailableHotels($date_debut,$date_fin,$region,$hotelRepository);
        return $this->render('home/hotels.html.twig', [
            'hotels'=>$hotels,
            'date_arrive' => $date_debut,
            'date_depart'=>$date_fin,
        ]);
        // return new Response(json_encode($chambre));
    }
    /**
     * @Route("/rooms/available/{id_hotel}/{date_debut}/{date_fin}", name="app_rooms_available")
     */
    public function get_available_rooms($id_hotel,$date_debut,$date_fin,ReservationRepository $reservationRepository,
                                         ChambreRepository $chambreRepository)
    {
        $rooms=$reservationRepository->findAvailableRooms($date_debut,$date_fin,$id_hotel,$chambreRepository);
        return $this->render('home/rooms.html.twig', [
            'rooms'=>$rooms,
            'date_arrive' => $date_debut,
            'date_depart'=>$date_fin,
        ]);
    }
    /**
     * @Route("/houses/available/{date_debut}/{date_fin}/{region}", name="app_houses_available")
     */
    public function get_available_houses($date_debut,$date_fin,$region,ReservationRepository $reservationRepository,
                                         MaisonRepository $maisonRepository)
    {

        $houses=$reservationRepository->findAvailableHouses($date_debut,$date_fin,$region,$maisonRepository);

        return $this->render('home/houses.html.twig', [
            'houses'=>$houses,
            'date_arrive' => $date_debut,
            'date_depart'=>$date_fin,
        ]);
    }
    /**
     * @Route("/cars/available/{date_debut}/{date_fin}/{region}", name="app_cars_available")
     */
    public function get_available_cars($date_debut,$date_fin,$region,ReservationRepository $reservationRepository,
                                       VoitureRepository $voitureRepository)
    {
        $cars=$reservationRepository->findAvailableCars($date_debut,$date_fin,$region,$voitureRepository);
        return $this->render('home/cars.html.twig', [
            'cars'=>$cars,
            'date_arrive' => $date_debut,
            'date_depart'=>$date_fin,
        ]);
    }
    /**
     * @Route("/events/available/{date_debut}/{region}", name="app_events_available")
     */
    public function get_available_events($date_debut,$region,ReservationRepository $reservationRepository,
                                         EvenementRepository $evenementRepository)
    {
        $events=$reservationRepository->findAvailableEvents($date_debut,$region,$evenementRepository);
        return $this->render('home/events.html.twig', [
            'events' => $events,
            'date_arrive' => $date_debut,
        ]);
    }






}
