<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use App\Service\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Dompdf\Dompdf;
class AdminController extends AbstractController
{


    /**
     * @Route("/admin/reservations/hotels", name="app_admin_reservations_hotels")
     */
    public function show_admin_reservations_hotels (ReservationRepository $reservationRepository): Response
    {

        $total_booked=$reservationRepository->findTotalBooked("hotel");
        $today_booked=$reservationRepository->findTodayBooked("hotel");
        $earnings=$reservationRepository->findEarnings("hotel");
        $cancled=$reservationRepository->findCancled("hotel");
        $date_today = new \DateTime();
        $this_year=$date_today->format("Y");

        //annually gains
        $data=$reservationRepository->findGainsYearly("hotel",$this_year)[0][1] | 0 ;
        $data1=$reservationRepository->findGainsYearly("hotel",$this_year-1)[0][1] | 0;
        $data2=$reservationRepository->findGainsYearly("hotel",$this_year-2)[0][1] | 0;
        $array_stats_annually =[0=>($this_year-2).'.'.$data2 ,1=>($this_year-1).'.'.$data1,2=>$this_year.'.'.$data];

        //monthly gains
        $months=['01','02','03','04','05','06','07','08','09','10','11','12'];
        $array_stats_monthly=[];
        $i=1;
        foreach($months as $value){
            $data=$reservationRepository->findGainsMonthly("hotel",$this_year,$value)[0][1] | 0;
            array_push($array_stats_monthly,$value.'.'.$data);
        }
        //comparison gains yearly
        $data3=$reservationRepository->findLossYearly("hotel",$this_year)[0][1] | 0 ;
        $data4=$reservationRepository->findGainsYearly("hotel",$this_year)[0][1] | 0 ;
        $array_stats_comparison_annually =[0=>("Gains").'.'.$data4 ,1=>("Loss").'.'.$data3];

        $reservations=$reservationRepository->findReservationsByType("hotel");
       return $this->render('admin/admin_reservations_hotels.html.twig', [
            'reservations' => $reservations,
            'total_booked'=>$total_booked,
            'today_booked'=>$today_booked,
            'earnings'=>$earnings,
            'cancled'=>$cancled,
            'anually_stats'=>$array_stats_annually,
            'monthly_stats'=>$array_stats_monthly,
           'comparison_annually'=>$array_stats_comparison_annually
        ]);
    }
    /**
     * @Route("/admin/reservations/houses", name="app_admin_reservations_houses")
     */
    public function show_admin_reservations_houses (ReservationRepository $reservationRepository): Response
    {
        $total_booked=$reservationRepository->findTotalBooked("house");
        $today_booked=$reservationRepository->findTodayBooked("house");
        $earnings=$reservationRepository->findEarnings("house");
        $cancled=$reservationRepository->findCancled("house");
        $date_today = new \DateTime();
        $this_year=$date_today->format("Y");

        //annually gains
        $data=$reservationRepository->findGainsYearly("house",$this_year)[0][1] | 0 ;
        $data1=$reservationRepository->findGainsYearly("house",$this_year-1)[0][1] | 0;
        $data2=$reservationRepository->findGainsYearly("house",$this_year-2)[0][1] | 0;
        $array_stats_annually =[0=>($this_year-2).'.'.$data2 ,1=>($this_year-1).'.'.$data1,2=>$this_year.'.'.$data];

        //monthly gains
        $months=['01','02','03','04','05','06','07','08','09','10','11','12'];
        $array_stats_monthly=[];
        foreach($months as $value){
            $data=$reservationRepository->findGainsMonthly("house",$this_year,$value)[0][1] | 0;
            array_push($array_stats_monthly,$value.'.'.$data);
        }
        //comparison gains yearly
        $data3=$reservationRepository->findLossYearly("house",$this_year)[0][1] | 0 ;
        $data4=$reservationRepository->findGainsYearly("house",$this_year)[0][1] | 0 ;
        $array_stats_comparison_annually =[0=>("Gains").'.'.$data4 ,1=>("Loss").'.'.$data3];
        $reservations=$reservationRepository->findReservationsByType("house");
        return $this->render('admin/admin_reservations_houses.html.twig', [
            'reservations' => $reservations,
            'total_booked'=>$total_booked,
            'today_booked'=>$today_booked,
            'earnings'=>$earnings,
            'cancled'=>$cancled,
            'anually_stats'=>$array_stats_annually,
            'monthly_stats'=>$array_stats_monthly,
            'comparison_annually'=>$array_stats_comparison_annually
        ]);
    }
    /**
     * @Route("/admin/reservations/cars", name="app_admin_reservations_cars")
     */
    public function show_admin_reservations_cars (ReservationRepository $reservationRepository): Response
    {
        $total_booked=$reservationRepository->findTotalBooked("car");
        $today_booked=$reservationRepository->findTodayBooked("car");
        $earnings=$reservationRepository->findEarnings("car");
        $cancled=$reservationRepository->findCancled("car");
        $date_today = new \DateTime();
        $this_year=$date_today->format("Y");

        //annually gains
        $data=$reservationRepository->findGainsYearly("car",$this_year)[0][1] | 0 ;
        $data1=$reservationRepository->findGainsYearly("car",$this_year-1)[0][1] | 0;
        $data2=$reservationRepository->findGainsYearly("car",$this_year-2)[0][1] | 0;
        $array_stats_annually =[0=>($this_year-2).'.'.$data2 ,1=>($this_year-1).'.'.$data1,2=>$this_year.'.'.$data];

        //monthly gains
        $months=['01','02','03','04','05','06','07','08','09','10','11','12'];
        $array_stats_monthly=[];
        foreach($months as $value){
            $data=$reservationRepository->findGainsMonthly("car",$this_year,$value)[0][1] | 0;
            array_push($array_stats_monthly,$value.'.'.$data);
        }
        //comparison gains yearly
        $data3=$reservationRepository->findLossYearly("car",$this_year)[0][1] | 0 ;
        $data4=$reservationRepository->findGainsYearly("car",$this_year)[0][1] | 0 ;
        $array_stats_comparison_annually =[0=>("Gains").'.'.$data4 ,1=>("Loss").'.'.$data3];

        $reservations=$reservationRepository->findReservationsByType("car");
        return $this->render('admin/admin_reservations_cars.html.twig', [
            'reservations' => $reservations,
            'total_booked'=>$total_booked,
            'today_booked'=>$today_booked,
            'earnings'=>$earnings,
            'cancled'=>$cancled,
            'anually_stats'=>$array_stats_annually,
            'monthly_stats'=>$array_stats_monthly,
            'comparison_annually'=>$array_stats_comparison_annually
        ]);
    }
    /**
     * @Route("/admin/reservations/events", name="app_admin_reservations_events")
     */
    public function show_admin_reservations_events (ReservationRepository $reservationRepository): Response
    {
        $total_booked=$reservationRepository->findTotalBooked("event");
        $today_booked=$reservationRepository->findTodayBooked("event");
        $earnings=$reservationRepository->findEarnings("event");
        $cancled=$reservationRepository->findCancled("event");
        $date_today = new \DateTime();
        $this_year=$date_today->format("Y");

        //annually gains
        $data=$reservationRepository->findGainsYearly("event",$this_year)[0][1] | 0 ;
        $data1=$reservationRepository->findGainsYearly("event",$this_year-1)[0][1] | 0;
        $data2=$reservationRepository->findGainsYearly("event",$this_year-2)[0][1] | 0;
        $array_stats_annually =[0=>($this_year-2).'.'.$data2 ,1=>($this_year-1).'.'.$data1,2=>$this_year.'.'.$data];

        //monthly gains
        $months=['01','02','03','04','05','06','07','08','09','10','11','12'];
        $array_stats_monthly=[];
        foreach($months as $value){
            $data=$reservationRepository->findGainsMonthly("event",$this_year,$value)[0][1] | 0;
            array_push($array_stats_monthly,$value.'.'.$data);
        }
        //comparison gains yearly
        $data3=$reservationRepository->findLossYearly("event",$this_year)[0][1] | 0 ;
        $data4=$reservationRepository->findGainsYearly("event",$this_year)[0][1] | 0 ;
        $array_stats_comparison_annually =[0=>("Gains").'.'.$data4 ,1=>("Loss").'.'.$data3];
        $reservations=$reservationRepository->findReservationsByType("event");
        return $this->render('admin/admin_reservations_events.html.twig', [
            'reservations' => $reservations,
            'total_booked'=>$total_booked,
            'today_booked'=>$today_booked,
            'earnings'=>$earnings,
            'cancled'=>$cancled,
            'anually_stats'=>$array_stats_annually,
            'monthly_stats'=>$array_stats_monthly,
            'comparison_annually'=>$array_stats_comparison_annually
        ]);
    }




}
