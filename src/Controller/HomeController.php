<?php

namespace App\Controller;

use App\Repository\ChambreRepository;
use App\Repository\HotelRepository;
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
    public function show_hotels(): Response
    {
        return $this->render('home/hotels.html.twig', [
            'controller_name' => 'HomeController',
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
     * @Route("/cars", name="app_cars")
     */
    public function show_cars(VoitureRepository $voitureRepository): Response
    {
        return $this->render('home/cars.html.twig');
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





}
