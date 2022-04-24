<?php

namespace App\Controller;
use App\Entity\Reservation;
use App\Entity\Transaction;
use App\Entity\Utilisateur;
use App\Form\PaymentType;
use App\Repository\ChambreRepository;
use App\Repository\EvenementRepository;
use App\Repository\MaisonRepository;
use App\Repository\TicketRepository;
use App\Repository\TransactionRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use phpDocumentor\Reflection\Types\Boolean;
use Stripe\Util\Util;
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
    public function create_transaction($type,$id_type,$id_user,$total,$date_arrive,$date_depart,Request $request,UtilisateurRepository $utilisateurRepository,
                          TransactionRepository $transactionRepository,ChambreRepository $chambreRepository,
                          TicketRepository $ticketRepository,VoitureRepository $voitureRepository,MaisonRepository $maisonRepository,
                          EntityManagerInterface $entityManager,FlashyNotifier $flashy ): Response
    {
        $user=$utilisateurRepository->find($id_user);
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
            try{
                $paymentIntent=$this->create_stripe_payment($transaction,$user);
                $transaction->setPaymentintentId($paymentIntent);
                $entityManager->persist($transaction);
                $entityManager->flush();
                return $this->redirectToRoute('app_reservation_create',['type'=>$type,'id_type'=>$id_type,'id_user'=>$id_user,'id_transaction'=>$transaction->getId(),
                    'date_arrive'=>$date_arrive,'date_depart'=>$date_depart,'total'=>$total]);
            } catch(\Exception $e){
                $flashy->warning($e->getMessage());
                return $this->render('transaction/payment.html.twig', [
                    'user' => $user,
                    'total' => $total,
                    'taux_avance'=>$transaction->getTauxAvance(),
                    'montant_avance'=>$transaction->getMontantPayeAvance(),
                    'form'=>$form->createView(),
                ]);
            }
        }


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
                                                    ,TransactionRepository $transactionRepository, EntityManagerInterface $em,FlashyNotifier $flashy): Response
    {

        $transaction=$transactionRepository->find($id_transaction);
        $user=$utilisateurRepository->find($id_user);
        $total=($transaction->getMontantPayeAvance()*100)/$transaction->getTauxAvance();
        $flashy->success('Réservation effectuée avec succés!');
        return $this->render('transaction/confirmation.html.twig', [
            'user' => $user,
            'transaction'=>$transaction,
            'total' => $total,
            'taux_avance'=>$transaction->getTauxAvance(),
            'montant_avance'=>$transaction->getMontantPayeAvance(),
        ]);
    }



    /**
     * @Route("/stripe/create/customer/{id_user}/{id_transaction}", name="app_stripe_create_customer")
     */
    public function customer($id_user,$id_transaction,UtilisateurRepository $utilisateurRepository,TransactionRepository $transactionRepository): Response
    {

        $user=$utilisateurRepository->find($id_user);
        $transaction=$transactionRepository->find($id_transaction);
        return new Response($this->create_stripe_payment($transaction,$user));
    }



    //-----logic----

    //-----payment----//
    public function create_stripe_payment(Transaction $transaction,Utilisateur $user)
    {
        $stripe = new \Stripe\StripeClient(
            'sk_test_51KW6yrADzWkQoAVyoXU2QxKdXriWn9eO6XH8SCttqQlpC7ZVzsd3DQ5zwq9LYr5wOgEJYQJ6KIZmLQXLCmBz6pqG00xeuLIrsT'
        );
            $payment_method='';
            $customer=$this->check_stripe_customer_exists($user,$stripe);
            if($customer){
                $payment_method=$this->check_payment_method_exists($transaction,$customer,$stripe);
                if(!$payment_method){
                    $new_payment_method=$this->create_card_payment_method($transaction, $stripe);
                    $payment_method=$this->attach_payment_method_to_customer($customer,$new_payment_method,$stripe);
                }

            }else{
                $customer=$this->create_stripe_customer($user);
                $new_payment_method=$this->create_card_payment_method($transaction, $stripe);
                $payment_method=$this->attach_payment_method_to_customer($customer,$new_payment_method,$stripe);
            }
            $this->update_customer_default_payment_method($customer,$payment_method,$stripe);
            $paymentIntent_waiting=$this->create_paymentIntent($transaction,$customer,$stripe);
            $paymentIntent=$this->confirm_paymentIntent($paymentIntent_waiting,$payment_method,$stripe);
            return $paymentIntent;
    }

    //----Customer----//
    public function check_stripe_customer_exists(Utilisateur $user,$stripe)
    {
        $customer=$stripe->customers->search([
        'query' => "email:'{$user->getEmail()}'",
        ]);
        if(sizeof($customer->data)==0)
            return '';
        return $customer->data[0]->id;
    }

    public function  create_stripe_customer(Utilisateur $user,$stripe){
        $customer=$stripe->customers->create([
            'name'=>$user->getNom()."".$user->getPrenom(),
            'email' => $user->getEmail(),
            'phone'=> $user->getNumTel(),
        ]);
        return $customer->data[0]->id;
    }

    public function update_customer_default_payment_method(String $customer_id,String $payment_method,$stripe){
        return $stripe->customers->update(
            $customer_id,
            ['invoice_settings' => ['default_payment_method' => $payment_method]]
        );
    }

    //----Payment Method----//
    public function  create_card_payment_method(Transaction $transaction,$stripe)
    {
        $encryption=$this->encrypt_data($transaction->getNumeroCarte());
        $payment_method=$stripe->paymentMethods->create([
            'type' => 'card',
            'card' => [
                'number' => $transaction->getNumeroCarte(),
                'exp_month' =>$this->change_month_format($transaction->getExpMois()),
                'exp_year' => $transaction->getExpAnnee(),
                'cvc' => $transaction->getCvc(),
            ],
            'metadata'=>[
                'card_identifier'=>$encryption
            ]
        ]);
        return $payment_method->id;
    }

    public function attach_payment_method_to_customer(String $customer_id,String $payment_method_id,$stripe){
        return $stripe->paymentMethods->attach($payment_method_id, [
            'customer' => $customer_id]
        )->id;
    }

    public function check_payment_method_exists(Transaction $transaction,$customer_id,$stripe){
        $encryption=$this->encrypt_data($transaction->getNumeroCarte());
        $decrypt=$this->decrypt_data($encryption);
        $payment_methods=$stripe->customers->allPaymentMethods($customer_id, ['type' => 'card']);
        $method_data='';
        foreach ($payment_methods as $method)  {
            if($method->metadata->card_identifier==$encryption){
                $method_data=$method->id;
                break;
            }
        }
        return $method_data;
    }

    //----Payment Intent----//
    public function create_paymentIntent(Transaction $transaction,String $customer_id,$stripe){
        $payment_intent=$stripe->paymentIntents->create([
            'amount' => $transaction->getMontantPayeAvance()*100,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'customer'=>$customer_id
        ]);
        return $payment_intent->id;
    }
    public function confirm_paymentIntent(String $paymentIntent_id,$payment_method,$stripe){
        return $stripe->paymentIntents->confirm(
            $paymentIntent_id,
            ['payment_method' =>$payment_method ]
        )->id;
    }


    //----Refund customer----//

    public function refund_customer(String $paymentIntent_id,$stripe){
        $refund=$stripe->refunds->create([
            'payment_intent' => $paymentIntent_id,
        ]);
        return $refund;
    }




    //Functions
    public function  encrypt_data(String $data){
        $simple_string = $data;
        $ciphering = "AES-128-CTR";
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = "alaa_encryption";
        $encryption = openssl_encrypt($simple_string, $ciphering,
            $encryption_key, $options, $encryption_iv);
        return $encryption;
    }
    public function decrypt_data(String $encryption){
        $ciphering = "AES-128-CTR";
        $options = 0;
        $decryption_iv = '1234567891011121';
        $decryption_key = "alaa_encryption";
        $decryption=openssl_decrypt ($encryption, $ciphering,
            $decryption_key, $options, $decryption_iv);
        return $decryption;
    }
    public function change_month_format(String $month ){
        if(strlen($month)==1){
            return "0".$month;
        }
        return $month;
    }


}


