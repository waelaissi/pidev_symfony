<?php

namespace App\Controller;
use App\Entity\Reservation;
use App\Entity\Transaction;
use App\Form\PaymentType;
use App\Repository\ChambreRepository;
use App\Repository\EvenementRepository;
use App\Repository\MaisonRepository;
use App\Repository\TicketRepository;
use App\Repository\TransactionRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transaction/{type}/{id_type}/{id_user}/{total}/{date_arrive}/{date_depart}", name="app_transaction")
     */
    public function index($type,$id_type,$id_user,$total,$date_arrive,$date_depart,Request $request,UtilisateurRepository $utilisateurRepository,
                          TransactionRepository $transactionRepository,ChambreRepository $chambreRepository,
                          TicketRepository $ticketRepository,VoitureRepository $voitureRepository,MaisonRepository $maisonRepository,
                          EntityManagerInterface $entityManager ): Response
    {
        $transaction=new Transaction();
        if($type=="event"){
            $transaction->setTauxAvance("100");
        }else{
            $transaction->setTauxAvance("20");
        }
        $transaction->setMontantPayeAvance($transactionRepository->calculate_amount_to_pay($total,$transaction->getTauxAvance()));
        $form = $this->createForm(PaymentType::class, $transaction);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transaction);
            $entityManager->flush();
            return $this->redirectToRoute('app_reservation_create',['type'=>$type,'id_type'=>$id_type,'id_user'=>$id_user,'id_transaction'=>$transaction->getId(),
                                                                          'date_arrive'=>$date_arrive,'date_depart'=>$date_depart,'total'=>$total]);
        }

        $user=$utilisateurRepository->find($id_user);
        return $this->render('transaction/payment.html.twig', [
            'user' => $user,
            'total' => $total,
            'taux_avance'=>$transaction->getTauxAvance(),
            'montant_avance'=>$transaction->getMontantPayeAvance(),
            'form'=>$form->createView(),
        ]);
    }


    /**
     * @Route("/transaction/confirmation/{id_user}/{id_transaction} ", name="app_transaction_confirmation")
     */
    public function show_transaction_confirmation($id_user,$id_transaction,UtilisateurRepository $utilisateurRepository
                                                    ,TransactionRepository $transactionRepository, EntityManagerInterface $em): Response
    {

        $transaction=$transactionRepository->find($id_transaction);
        $user=$utilisateurRepository->find($id_user);
        $total=($transaction->getMontantPayeAvance()*100)/$transaction->getTauxAvance();
        return $this->render('transaction/confirmation.html.twig', [
            'user' => $user,
            'transaction'=>$transaction,
            'total' => $total,
            'taux_avance'=>$transaction->getTauxAvance(),
            'montant_avance'=>$transaction->getMontantPayeAvance(),
        ]);
    }



}
